<?php

namespace App\Http\Controllers;

use App\Models\PaySalary;
use App\Models\User;
use Carbon\Carbon;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;

class PaySalaryController extends Controller
{
    public function index()
    {
        // Load users for dropdown
        $users = User::select('id', 'name')
            ->where('super_user', '!=', 1)
            ->get();
        $paySalaries = PaySalary::with('user')->latest()->get();
        return view('admin.payroll.index', compact('users', 'paySalaries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'salary_month' => 'required|integer|min:1|max:12',
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
        ], [
            'user_id.required' => '၀န်ထမ်းအမည်ကို ရွေးရန်လိုအပ်ပါသည်။',
            'user_id.exists' => 'ရွေးထားသော ၀န်ထမ်းကို မတွေ့ရှိနိုင်ပါ။',
            'salary_month.required' => 'လစာပေးချေသည့်လ ကိုရွေးပါ။',
            'salary_month.integer' => 'လစာပေးချေသည့်လ သည် ကိန်းဂဏန်းဖြစ်ရပါမည်။',
            'basic_salary.required' => 'အခြေခံလစာ ဖြည့်ရန်လိုအပ်ပါသည်။',
            'basic_salary.numeric' => 'အခြေခံလစာ သည် ငွေပမာဏဖြစ်ရပါမည်။',
            'allowances.numeric' => 'အပိုထောက်ပံ့ငွေ သည် ငွေပမာဏဖြစ်ရပါမည်။',
            'payment_method.string' => 'ငွေပေးချေမှုနည်းလမ်း သည် စာသားဖြစ်ရပါမည်။',
        ]);

        // Check if salary for this user and month already exists
        $exists = PaySalary::where('user_id', $request->user_id)
            ->where('salary_month', $request->salary_month)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'ဤ၀န်ထမ်းအတွက် အဆိုပါလ၏ လစာကို ရှိပြီးသားဖြစ်သည်။');
        }

        // Calculate leave duration for the requested salary month
        $totalDuration = LeaveRequest::where('user_id', $request->user_id)
            ->whereNull('leave_type_id')
            ->where('status', 'approved')
            ->whereMonth('created_at', $request->salary_month)
            ->sum('duration');

        $basicSalary = $request->basic_salary;
        $allowances = $request->allowances ?? 0;
        $deductionPerDay = ($basicSalary * 0.02);
        $totalDeduction = $deductionPerDay * $totalDuration;

        // Include allowances in net salary
        $netSalary = $basicSalary + $allowances - $totalDeduction;

        PaySalary::create([
            'user_id' => $request->user_id,
            'salary_month' => $request->salary_month,
            'basic_salary' => $basicSalary,
            'allowances' => $allowances,
            'deductions' => $totalDeduction,
            'payment_method' => $request->payment_method,
            'net_salary' => $netSalary,
        ]);

        return redirect()->back()->with('success', 'လစာကို အောင်မြင်စွာ သိမ်းဆည်းပြီးပါပြီ။');
    }

}
