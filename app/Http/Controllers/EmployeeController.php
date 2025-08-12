<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use App\Models\PaySalary;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmployeeController extends Controller
{

    private function getUser()
    {
        return auth()->user();
    }
    public function showProfile()
    {
        $user = $this->getUser();
        return view('employee.profile.show', compact('user'));
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
        $user = auth()->user();
        $leaveTypes = LeaveType::all();
        $leaveRequests = LeaveRequest::where('user_id', $user->id)
            ->with('leaveType') // eager load leave type
            ->get();

        return view('employee.leave.index', compact('user', 'leaveTypes', 'leaveRequests'));
    }


    public function storeLeaveRequest(Request $request)
    {
        $rules = [
            'leave_type' => 'required|in:casual,special',
            'description' => 'nullable|string',
            'img' => 'nullable|image|max:2048',
        ];

        if ($request->leave_type === 'casual') {
            $rules['from_date'] = 'required|date|after:today';
            $rules['to_date'] = 'required|date|after_or_equal:from_date';
            $rules['to_date'][] = function ($attribute, $value, $fail) use ($request) {
                $fromDate = \Carbon\Carbon::parse($request->from_date);
                $toDate = \Carbon\Carbon::parse($value);
                if ($fromDate->format('Y-m') !== $toDate->format('Y-m')) {
                    $fail('From date နှင့် To date တို့သည် တစ်လအတွင်း ဖြစ်ရပါမည်။');
                }
            };
        } else {
            $rules['leave_type_id'] = 'required|exists:leave_types,id';
            $rules['from_date'] = 'required|date|after:today';
        }

        $messages = [
            'leave_type.required' => 'ခွင့်အမျိုးအစား ကိုရွေးချယ်ရန် လိုအပ်သည်။',
            'leave_type.in' => 'ခွင့်အမျိုးအစား သည် တရားဝင်မဟုတ်ပါ။',

            'description.string' => 'ဖော်ပြချက်သည် စာသားဖြစ်ရမည်။',

            'img.image' => 'ဓာတ်ပုံဖိုင်သာ တင်သွင်းနိုင်သည်။',
            'img.max' => 'ဓာတ်ပုံ၏ အရွယ်အစားသည် 2MB ထက် မကျော်ရပါ။',

            'from_date.required' => 'စတင်နေ့ကို ဖြည့်ရန် လိုအပ်သည်။',
            'from_date.date' => 'စတင်နေ့သည် တရားဝင်ရက်စွဲဖြစ်ရမည်။',
            'from_date.after' => 'စတင်နေ့သည် ယနေ့နောက်မှသာ ရွေးချယ်နိုင်ပါသည်။',

            'to_date.required' => 'ဆုံးနိမ့်နေ့ကို ဖြည့်ရန် လိုအပ်သည်။',
            'to_date.date' => 'ဆုံးနိမ့်နေ့သည် တရားဝင်ရက်စွဲဖြစ်ရမည်။',
            'to_date.after_or_equal' => 'ဆုံးနိမ့်နေ့သည် စတင်နေ့နှင့် ညီညွတ်ရမည် သို့မဟုတ် နောက်ရက်ဖြစ်ရမည်။',

            'leave_type_id.required' => 'ခွင့်အမျိုးအစား ကိုရွေးချယ်ရန် လိုအပ်သည်။',
            'leave_type_id.exists' => 'ရွေးချယ်ထားသော ခွင့်အမျိုးအစား မရှိပါ။',
        ];

        $validated = $request->validate($rules, $messages);

        $leaveRequest = new LeaveRequest();
        $leaveRequest->user_id = auth()->id();

        if ($request->leave_type === 'casual') {
            $leaveRequest->leave_type_id = null;
            $leaveRequest->from_date = $request->from_date;
            $leaveRequest->to_date = $request->to_date;
            $leaveRequest->duration = \Carbon\Carbon::parse($request->from_date)
                ->diffInDays(\Carbon\Carbon::parse($request->to_date)) + 1;
        } else {
            $leaveType = LeaveType::findOrFail($request->leave_type_id);
            $leaveRequest->leave_type_id = $leaveType->id;
            $leaveRequest->from_date = $request->from_date;
            $leaveRequest->duration = $leaveType->max_days; // use max_days as duration
            $leaveRequest->to_date = Carbon::parse($request->from_date)->addDays($leaveType->max_days - 1)->format('Y-m-d');
        }

        $leaveRequest->description = $request->description;

        if ($request->hasFile('img')) {
            $path = $request->file('img')->store('leave_docs', 'public');
            $leaveRequest->img = $path;
        }

        $leaveRequest->status = 'pending';
        $leaveRequest->save();

        return response()->json(['message' => 'ခွင့်လျှောက်လွှာ အောင်မြင်စွာ တင်သွင်းပြီးပါပြီ။']);
    }

    public function showSalaryList()
    {
        $paySalaries = PaySalary::with('user')
        ->where('user_id', Auth::id())
        ->latest()
        ->get();

        return view('employee.salary.list', compact('paySalaries'));
    }
}
