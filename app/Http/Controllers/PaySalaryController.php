<?php

namespace App\Http\Controllers;

use App\Models\PaySalary;
use App\Models\User;
use Carbon\Carbon;
use App\Models\LeaveRequest;
use App\Models\Web;
use Illuminate\Http\Request;

class PaySalaryController extends Controller
{
    public function index()
    {
        $web = Web::first();
        // Load users for dropdown
        $users = User::select('id', 'name')
            ->where('super_user', '!=', 1)
            ->get();
        $paySalaries = PaySalary::with('user')->latest()->get();
        return view('admin.payroll.index', compact('users', 'paySalaries', 'web'));
    }

    public function store(Request $request)
    {
        // --- Validation ---
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'salary_month' => 'required|integer|min:1|max:12',
            'salary_year' => 'required|integer|min:2000|max:2100', // added year
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
        ], [
            'user_id.required' => '၀န်ထမ်းအမည်ကို ရွေးရန်လိုအပ်ပါသည်။',
            'user_id.exists' => 'ရွေးထားသော ၀န်ထမ်းကို မတွေ့ရှိနိုင်ပါ။',
            'salary_month.required' => 'လစာပေးချေသည့်လ ကိုရွေးပါ။',
            'salary_month.integer' => 'လစာပေးချေသည့်လ သည် ကိန်းဂဏန်းဖြစ်ရပါမည်။',
            'salary_year.required' => 'လစာပေးချေသည့်နှစ် ကိုရွေးပါ။',
            'salary_year.integer' => 'လစာပေးချေသည့်နှစ် သည် ကိန်းဂဏန်းဖြစ်ရပါမည်။',
            'basic_salary.required' => 'အခြေခံလစာ ဖြည့်ရန်လိုအပ်ပါသည်။',
            'basic_salary.numeric' => 'အခြေခံလစာ သည် ငွေပမာဏဖြစ်ရပါမည်။',
            'allowances.numeric' => 'အပိုထောက်ပံ့ငွေ သည် ငွေပမာဏဖြစ်ရပါမည်။',
            'payment_method.string' => 'ငွေပေးချေမှုနည်းလမ်း သည် စာသားဖြစ်ရပါမည်။',
        ]);

        // --- Check duplicate salary entry ---
        $exists = PaySalary::where('user_id', $request->user_id)
            ->where('salary_month', $request->salary_month)
            ->where('salary_year', $request->salary_year)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'ဤ၀န်ထမ်းအတွက် အဆိုပါလ၏ လစာကို ရှိပြီးသားဖြစ်သည်။');
        }

        $userId = $request->user_id;
        $month = $request->salary_month;
        $year = $request->salary_year;
        $basicSalary = $request->basic_salary;
        $allowances = $request->allowances ?? 0;

        // --- Total days in month ---
        $totalDay = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        // --- Calculate Leaves ---
        $medical_leave = LeaveRequest::where('user_id', $userId)
            ->whereMonth('from_date', $month)
            ->whereYear('from_date', $year)
            ->where('req_type', 'medical-certificate')
            ->sum('duration');

        $no_pay_leave = LeaveRequest::where('user_id', $userId)
            ->whereMonth('from_date', $month)
            ->whereYear('from_date', $year)
            ->where('req_type', 'no-pay')
            ->sum('duration');

        // --- Calculate average salary for the past 12 months ---
        $startDate = \Carbon\Carbon::create($year, $month, 1)->subYear(); // start from previous year same month
        $endDate = \Carbon\Carbon::create($year, $month, 1)->subDay();    // end at last day of previous month

        $averagesalary = PaySalary::where('user_id', $userId)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->whereRaw("STR_TO_DATE(CONCAT(salary_year, '-', salary_month, '-01'), '%Y-%m-%d') BETWEEN ? AND ?", [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
                });
            })
            ->sum('net_salary');

        // --- Attendance Days ---
        $attendDay = max(0, $totalDay - ($medical_leave + $no_pay_leave));

        // --- Salary Calculations ---
        $eachDayFee = $basicSalary / $totalDay;
        $attendDayFee = $eachDayFee * $attendDay;

        $seconds = $averagesalary / 12;
        $secondCalc = $seconds / 2;
        $ttl = $secondCalc / $totalDay;
        $medicalDurationFee = $medical_leave * $ttl;

        $noPayFee = $eachDayFee * $no_pay_leave;

        $netSalary = $attendDayFee + $medicalDurationFee + $allowances - $noPayFee;

        // --- Save to database ---
        PaySalary::create([
            'user_id' => $userId,
            'salary_month' => $month,
            'salary_year' => $year,
            'basic_salary' => $basicSalary,
            'allowances' => $allowances,
            'medical_de' => $medicalDurationFee,
            'no_pay_de' => $noPayFee,
            'deductions' => $noPayFee,
            'payment_method' => $request->payment_method,
            'net_salary' => $netSalary,
        ]);

        return redirect()->back()->with('success', 'လစာကို အောင်မြင်စွာ သိမ်းဆည်းပြီးပါပြီ။');
    }
}
