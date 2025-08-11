<?php

namespace App\Http\Controllers;

use App\Models\EmployeeHousing;
use Illuminate\Http\Request;

class EmployeeHousingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function employeeHouseList(Request $request)
    {
        $status = $request->get('status');

        $query = EmployeeHousing::with('user');

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $housings = $query->latest()->get();

        return view('admin.staff.house', compact('housings', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createForm()
    {
        $userId = auth()->id();
        $requests = EmployeeHousing::where('user_id', $userId)->get();
        $hasRequest = $requests->isNotEmpty();
        return view('employee.housing.create', compact('requests', 'hasRequest'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeForm(Request $request)
    {
        $userId = auth()->id();

        $exists = EmployeeHousing::where('user_id', $userId)
            ->where(function ($query) {
                $query->where('status', EmployeeHousing::STATUS_PENDING)
                    ->orWhere('status', EmployeeHousing::STATUS_APPROVED);
            })
            ->exists();

        if ($exists) {
            return redirect()->back()->with('duplicate', 'လျှောက်လွှာကို တကြိမ်လျှောက်ထားပီး ဖြစ်ပါသည်။');
        }

        $validated = $request->validate([
            'family_member' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required|string',
            'submit_date' => 'required|date',
            'township' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $lower = mb_strtolower($value);
                    if (str_contains($value, 'himthada') || str_contains($value, 'ဟင်္သာတ')) {
                        $fail('ဟင်္သာတ မြို့နယ် အတွက် လျှောက်လွှာတင်ဖောင် ပိတ်ထားပါသည်');
                    }
                },
            ],
        ], [
            'family_member.required' => 'မိသားစုဝင်အချက်အလက်ကို ဖြည့်ရန် လိုအပ်ပါသည်။',
            'family_member.string' => 'မိသားစုဝင်အချက်အလက်သည် စာသားပဲ ဖြစ်ရပါမည်။',
            'family_member.max' => 'မိသားစုဝင်အချက်အလက်သည် အလျားအများဆုံး ၂၅၅ လုံးသာ ဖြစ်နိုင်ပါသည်။',
            'image.required' => 'ပုံကို ဖြည့်ရန် လိုအပ်ပါသည်။',
            'image.image' => 'ပုံသည် image file ဖြစ်ရမည်။',
            'image.mimes' => 'ပုံဖိုင်သည် jpeg, png, jpg, gif အမျိုးအစားသာဖြစ်ရမည်။',
            'image.max' => 'ပုံဖိုင်သည် 2MB ထက် မကြီးသင့်ပါ။',

            'description.required' => 'ဖော်ပြချက်ကို ဖြည့်ရန် လိုအပ်ပါသည်။',
            'description.string' => 'ဖော်ပြချက်သည် စာသားပဲ ဖြစ်ရပါမည်။',

            'submit_date.required' => 'တင်သွင်းသည့်ရက်စွဲကို ဖြည့်ရန် လိုအပ်ပါသည်။',
            'submit_date.date' => 'တင်သွင်းသည့်ရက်စွဲသည် ရက်စွဲပုံစံဖြစ်ရမည်။',

            'township.required' => 'မြို့နယ်ကို ဖြည့်ရန် လိုအပ်ပါသည်။',
            'township.string' => 'မြို့နယ်သည် စာသားပဲ ဖြစ်ရပါမည်။',
            'township.max' => 'မြို့နယ်သည် အလျားအများဆုံး ၂၅၅ လုံးသာ ဖြစ်နိုင်ပါသည်။',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('house', 'public');
            $validated['house_hold_img'] = $path;
        }

        $validated['user_id'] = $userId;
        $validated['status'] = EmployeeHousing::STATUS_PENDING;

        EmployeeHousing::create($validated);

        return redirect()->back()->with('success', 'တောင်းဆိုမှုအောင်မြင်စွာ တင်သွင်းပြီးပါပြီ။');
    }



    public function approveHouseRequest(Request $request, $id)
    {
        $request->validate([
            'approved_date' => 'required|date',
        ]);

        $housing = EmployeeHousing::findOrFail($id);
        $housing->status = EmployeeHousing::STATUS_APPROVED;
        $housing->approved_date = $request->input('approved_date');
        $housing->save();

        return redirect()->back()->with('success', 'လျှောက်လွှာ လက်ခံပြီးပါပြီ!');
    }



    public function rejectHouseRequest($id)
    {
        $housing = EmployeeHousing::findOrFail($id);
        $housing->status = EmployeeHousing::STATUS_REJECT;
        $housing->save();

        return redirect()->back()->with('success', 'လျှောက်လွှာ ငြင်းပယ်ပြီးပါပြီ!');
    }


    public function showHouseRequest($id)
    {
        $housing = EmployeeHousing::with('user')->findOrFail($id);

        return response()->json($housing);
    }
}
