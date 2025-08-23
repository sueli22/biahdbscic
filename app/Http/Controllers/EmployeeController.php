<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use App\Models\PaySalary;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Web;

class EmployeeController extends Controller
{

    private function getUser()
    {
        return auth()->user();
    }
    public function showProfile()
    {
        $web = Web::first();
        $user = $this->getUser();
        return view('employee.profile.show', compact('user', 'web'));
    }

    public function updateImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        $user = auth()->user();

        // Delete old image if exists
        if ($user->image && Storage::disk('public')->exists($user->image)) {
            Storage::disk('public')->delete($user->image);
        }

        $path = $request->file('image')->store('staff', 'public');

        $user->image = $path;
        $user->save();

        return redirect()->back()->with('success', 'ပုံကို အောင်မြင်စွာ ပြောင်းလဲပြီးပါပြီ။');
    }

    public function showLeaveRequestForm()
    {
        $leaveRules = [
            'shaung'              => 10,   // တစ်နှစ် ၁၀ ရက်
            'contagious'          => 30,   // ၃၀ ရက်
            'long-service'        => 60,   // ၂ လ
            'medical-certificate' => 365,  // ၁၂ လ
            'maternity'           => 180,  // ၆ လ
            'disability'          => 730,  // ၂ နှစ်
            'no-pay'              => null,   // တစ်နှစ် ၁၀ ရက်
        ];

        $web = Web::first();
        $user = auth()->user();
        $leaveTypes = LeaveType::all();
        $leaveRequests = LeaveRequest::where('user_id', $user->id)
            ->with('leaveType') // eager load leave type
            ->get();

        // Usage
        $leaveUsages = LeaveRequest::selectRaw('req_type, SUM(duration) as used_days')
            ->where('user_id', $user->id)
            ->whereIn('status', ['approved', 'pending'])
            ->whereYear('from_date', now()->year) // သာမန် leave များ yearly reset
            ->groupBy('req_type')
            ->pluck('used_days', 'req_type')
            ->toArray();

        // Balance
        $leaveBalances = [];
        foreach ($leaveRules as $type => $maxDays) {
            $used = $leaveUsages[$type] ?? 0;
            $left = max($maxDays - $used, 0);

            $leaveBalances[$type] = [
                'max'  => $maxDays,
                'used' => $used,
                'left' => $left,
            ];
        }


        return view('employee.leave.index', compact('user', 'leaveTypes', 'leaveRequests', 'web', 'leaveBalances'));
    }


    public function storeLeaveRequest(Request $request)
    {
        $user = auth()->user();

        $messages = [
            'leave_type.required' => 'ခွင့်အမျိုးအစား ကိုရွေးချယ်ရန် လိုအပ်သည်။',
            'leave_type.in' => 'ခွင့်အမျိုးအစား သည် တရားဝင်မဟုတ်ပါ။',
            'description.string' => 'ဖော်ပြချက်သည် စာသားဖြစ်ရမည်။',
            'img.image' => 'ဓာတ်ပုံဖိုင်သာ တင်သွင်းနိုင်သည်။',
            'img.max' => 'ဓာတ်ပုံ၏ အရွယ်အစားသည် 2MB ထက် မကျော်ရပါ။',
            'from_date.required' => 'စတင်နေ့ကို ဖြည့်ရန် လိုအပ်သည်။',
            'from_date.date' => 'စတင်နေ့သည် တရားဝင်ရက်စွဲဖြစ်ရမည်။',
            'from_date.after' => 'စတင်နေ့သည် ယနေ့နောက်မှသာ ရွေးချယ်နိုင်ပါသည်။',
            'to_date.required' => 'နောက်ဆုံးခွင်ံရက်ကို ဖြည့်ရန် လိုအပ်သည်။',
            'to_date.date' => 'နောက်ဆုံးခွင်ံရက်သည် တရားဝင်ရက်စွဲဖြစ်ရမည်။',
            'to_date.after_or_equal' => 'နောက်ဆုံးခွင်ံရက်သည် စတင်နေ့နှင့် ညီညွတ်ရမည် သို့မဟုတ် နောက်ရက်ဖြစ်ရမည်။',
        ];

        // Validation
        $request->validate([
            'req_type'   => 'required|string',
            'from_date'  => 'required|date|after:today',
            'to_date'    => $request->req_type === 'maternity' ? 'nullable|date' : 'required|date|after_or_equal:from_date',
            'description' => 'nullable|string|max:255',
            'img'        => 'nullable|image|max:2048',
        ], $messages);

        $from = Carbon::parse($request->from_date);
        $to   = $request->to_date ? Carbon::parse($request->to_date) : $from;
        $days = $from->diffInDays($to) + 1;

        // Check overlapping leaves
        $overlap = LeaveRequest::where('user_id', $user->id)
            ->whereIn('status', ['approved', 'pending'])
            ->where(function ($q) use ($from, $to) {
                $q->whereBetween('from_date', [$from, $to])
                    ->orWhereBetween('to_date', [$from, $to])
                    ->orWhere(function ($q2) use ($from, $to) {
                        $q2->where('from_date', '<=', $from)
                            ->where('to_date', '>=', $to);
                    });
            })
            ->exists();

        if ($overlap) {
            return response()->json([
                'errors' => ['general' => ['သတ်မှတ်ထားသောရက်များတွင် ခွင့်တောင်းထားပြီးသားရှိပါသည်။']]
            ], 422);
        }

        // Merge SHAUNG leave if new leave is other type
        if ($request->req_type !== 'shaung') {
            $overlappingShaung = LeaveRequest::where('user_id', $user->id)
                ->where('req_type', 'shaung')
                ->whereIn('status', ['approved', 'pending'])
                ->where(function ($q) use ($from, $to) {
                    $q->where(function ($q1) use ($from, $to) {
                        $q1->whereDate('from_date', '<=', $to->copy()->addDay())
                            ->whereDate('to_date', '>=', $from->copy()->subDay());
                    });
                })
                ->get();


            logger()->info(['dd' => $overlappingShaung]);

            if ($overlappingShaung->count() > 0) {
                // Keep old SHAUNG start date
                $oldFrom = Carbon::parse($overlappingShaung->first()->from_date);
                // Use the max of old SHAUNG end and new leave end
                $oldTo = $overlappingShaung->max(function ($item) {
                    return Carbon::parse($item->to_date);
                });
                $newTo = Carbon::parse($request->to_date);
                $from = $oldFrom;
                $to = $newTo->greaterThan($oldTo) ? $newTo : $oldTo;
                $days = $from->diffInDays($to) + 1;

                // Delete all old overlapping SHAUNG leaves
                foreach ($overlappingShaung as $shaung) {
                    $shaung->delete();
                }
            }
        }

        // Leave type rules
        switch ($request->req_type) {
            case 'shaung':
                $from = Carbon::parse($request->from_date);
                $to   = Carbon::parse($request->to_date ?? $from);
                if ($from->isWeekend() || $to->isWeekend()) {
                    return response()->json([
                        'errors' => ['general' => ['ခွင့် စတင်ရက် သို့မဟုတ် နောက်ဆုံးရက်သည် စနေနေ့/တနင်္ဂနွေနေ့ မဖြစ်ရပါ။']]
                    ], 422);
                }
                if ($days > 3) {
                    return response()->json(['errors' => ['general' => ['တခါတင် အများဆုံး 3ရက်သာယူနိုင်သည်။']]], 422);
                }
                $used = LeaveRequest::where('user_id', $user->id)
                    ->where('req_type', $request->req_type)
                    ->whereYear('from_date', now()->year)
                    ->whereIn('status', ['approved', 'pending'])
                    ->sum(DB::raw('DATEDIFF(to_date, from_date) + 1'));
                if ($used + $days > 10) {
                    return response()->json(['errors' => ['general' => ['ခွင့်သည် တနှစ်လျှင် ၁၀ရက်သာ ရရှိနိုင်ပါသည်']]], 422);
                }
                $lastLeave = LeaveRequest::where('user_id', $user->id)
                    ->where('req_type', $request->req_type)
                    ->whereIn('status', ['approved', 'pending'])
                    ->latest('to_date')
                    ->first();
                if ($lastLeave && $from->diffInDays(Carbon::parse($lastLeave->to_date)) < 2) {
                    return response()->json(['errors' => ['general' => ['တရက်ပြန်အလုပ်တက်ပြီးမှ ထပ်တင်နိုင်သည်။']]], 422);
                }
                break;

            case 'infection':
                if ($days > 30) return response()->json(['errors' => ['general' => ['ကူးစက်ရောဂါကာကွယ်ခွင့် 30ရက်ထပ်မကျော်ရ။']]], 422);
                break;

            case 'seniority':
                if ($days > 60) return response()->json(['errors' => ['general' => ['လုပ်သက်ခွင့် ၂လထပ်မကျော်ရ။']]], 422);
                break;

            case 'medical':
                if ($days > 365) return response()->json(['errors' => ['general' => ['ဆေးလက်မှတ်ခွင့် ၁၂လထပ်မကျော်ရ။']]], 422);
                break;

            case 'maternity':
                if ($days > 180) return response()->json(['errors' => ['general' => ['မီးဖွားခွင့် ၆လထပ်မကျော်ရ။']]], 422);

                break;

            case 'disability':
                if ($days > 730) return response()->json(['errors' => ['general' => ['အထူးမသန်စွမ်းခွင့် ၂နှစ်ထပ်မကျော်ရ။']]], 422);
                break;
        }

        // Save leave
        $leave = LeaveRequest::create([
            'user_id'     => $user->id,
            'req_type'    => $request->req_type,
            'from_date'   => $from->format('Y-m-d'),
            'to_date'     => $to->format('Y-m-d'),
            'duration'    => $days,
            'description' => $request->description,
            'status'      => 'pending',
        ]);

        return response()->json([
            'message' => 'ခွင့်တင်သွင်းမှု အောင်မြင်စွာ သိမ်းဆည်းပြီးပါပြီ။',
            'leave' => $leave
        ]);
    }


    public function suggestToDate(Request $request)
    {
        $request->validate([
            'req_type' => 'required|string',
            'from_date' => 'required|date'
        ]);

        $limits = [
            'long-service' => 60,
            'medical-certificate' => 365,
            'maternity' => 180,
            'disability' => 730,
            'no-pay' => 10,
            'contagious' => 30,
            'volunteer-sick' => 5,
            'study' => 30
        ];

        $from = Carbon::parse($request->from_date);
        $reqType = $request->req_type;

        if (isset($limits[$reqType])) {
            $to = $from->copy()->addDays($limits[$reqType] - 1);
            return response()->json([
                'to_date' => $to->format('Y-m-d'),
                'max_days' => $limits[$reqType]
            ]);
        }

        return response()->json(['to_date' => null]);
    }


    public function showSalaryList()
    {
        $web = Web::first();
        $paySalaries = PaySalary::with('user')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('employee.salary.list', compact('paySalaries', 'web'));
    }
}
