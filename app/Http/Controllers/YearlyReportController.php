<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\YearlyReport;
use App\Models\Web;

class YearlyReportController extends Controller
{

    public function show($id)
    {
        $report = YearlyReport::findOrFail($id);
        return response()->json($report);
    }

    public function homeYearlyReport()
    {
        $web = Web::first();
        return view('home.report', compact('web'));
    }

    public function index()
    {
        $web = Web::first();
        return view('admin.reports.index', compact('web'));
    }

    public function list(Request $request)
    {
        $query = YearlyReport::query();

        if ($request->filled('from')) {
            $query->where('from', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->where('to', '<=', $request->to);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'from' => 'required|integer|digits:4|lt:to',
            'to' => 'required|integer|digits:4',
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'start_month' => 'required|string|max:255',
            'end_month' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'operation_year' => 'required|integer|digits:4|between:1901,2155',
        ], [
            'from.required' => 'စတင်မည့်နှစ်ထည့်ရန်လိုအပ်ပါသည်။',
            'from.integer' => 'စတင်မည့်နှစ်သည် ဂဏန်းဖြစ်ရပါမည်။',
            'from.digits' => 'စတင်မည့်နှစ်သည် ဂဏန်း 4 လုံးဖြစ်ရပါမည်။',
            'from.lt' => 'စတင်မည့်နှစ်သည် ပြီးဆုံးမည့်နှစ်ထက် သေးရပါမည်။',

            'to.required' => 'ပြီးဆုံးမည့်နှစ်ထည့်ရန်လိုအပ်ပါသည်။',
            'to.integer' => 'ပြီးဆုံးမည့်နှစ်သည် ဂဏန်းဖြစ်ရပါမည်။',
            'to.digits' => 'ပြီးဆုံးမည့်နှစ်သည် ဂဏန်း 4 လုံးဖြစ်ရပါမည်။',

            'name.required' => 'စီမံကိန်းအမည်ထည့်ရန်လိုအပ်ပါသည်။',
            'location.required' => 'တည်နေရာထည့်ရန်လိုအပ်ပါသည်။',
            'start_month.required' => 'စတင်မည့်ကာလထည့်ရန်လိုအပ်ပါသည်။',
            'end_month.required' => 'ပြီးဆုံးမည့်ကာလထည့်ရန်လိုအပ်ပါသည်။',
            'department.required' => 'ဆောင်ရွက်မည့်ဌာန/အဖွဲ့အစည်းထည့်ရန်လိုအပ်ပါသည်။',

            'total_investment.required' => 'စုစုပေါင်းရင်းနှီးမြှုပ်နှံမည့်ငွေထည့်ရန်လိုအပ်ပါသည်။',
            'total_investment.numeric' => 'စုစုပေါင်းရင်းနှီးမြှုပ်နှံမည့်ငွေကို နံပါတ်ဖြင့်ထည့်ရန်လိုအပ်ပါသည်။',

            'operation_year.required' => 'ဆောင်ရွက်မည့်နှစ်ထည့်ရန်လိုအပ်ပါသည်။',
            'operation_year.integer' => 'ဆောင်ရွက်မည့်နှစ်သည် ဂဏန်းဖြစ်ရပါမည်။',
            'operation_year.digits' => 'ဆောင်ရွက်မည့်နှစ်သည် ဂဏန်း 4 လုံးဖြစ်ရပါမည်။',
            'operation_year.between' => 'ဆောင်ရွက်မည့်နှစ်သည် 1901 မှ 2155 အတွင်းရှိရပါမည်။',

            'regional_budget.required' => 'တိုင်းဒေသကြီးဘတ်ဂျက်ထည့်ရန်လိုအပ်ပါသည်။',
            'regional_budget.numeric' => 'တိုင်းဒေသကြီးဘတ်ဂျက်ကို နံပါတ်ဖြင့်ထည့်ရန်လိုအပ်ပါသည်။',

            'tender_price.required' => 'တင်ဒါအောင်မြင်သည့်စျေးနှုန်းထည့်ရန်လိုအပ်ပါသည်။',
            'tender_price.numeric' => 'တင်ဒါအောင်မြင်သည့်စျေးနှုန်းကို နံပါတ်ဖြင့်ထည့်ရန်လိုအပ်ပါသည်။',
        ]);

        YearlyReport::create($data);

        return response()->json([
            'success' => true,
            'message' => 'စီမံကိန်းကို အောင်မြင်စွာ ထည့်ပြီးပါပြီ။'
        ]);
    }

    public function destroy($id)
    {
        $report = YearlyReport::findOrFail($id);
        $report->delete();

        return redirect()->back()->with('success', 'Report deleted successfully.');
    }

    public function edit($id)
    {
        $report = YearlyReport::find($id);

        if (!$report) {
            return response()->json(['error' => 'Data မရှိပါ'], 404);
        }

        return response()->json($report);
    }

    // Update → handle validation & save
    public function update(Request $request, $id)
    {
        $report = YearlyReport::find($id);

        if (!$report) {
            return response()->json(['error' => 'Data မရှိပါ'], 404);
        }

        // Validation
        $validated = $request->validate([
            'from' => 'required|integer|digits:4|lt:to',
            'to' => 'required|integer|digits:4',
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'start_month' => 'required|string|max:255',
            'end_month' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'operation_year' => 'required|integer|digits:4|between:1901,2155',
        ], [
            'from.required' => 'စတင်မည့်နှစ်ထည့်ရန်လိုအပ်ပါသည်။',
            'from.integer' => 'စတင်မည့်နှစ်သည် ဂဏန်းဖြစ်ရပါမည်။',
            'from.digits' => 'စတင်မည့်နှစ်သည် ဂဏန်း 4 လုံးဖြစ်ရပါမည်။',
            'from.lt' => 'စတင်မည့်နှစ်သည် ပြီးဆုံးမည့်နှစ်ထက် သေးရပါမည်။',

            'to.required' => 'ပြီးဆုံးမည့်နှစ်ထည့်ရန်လိုအပ်ပါသည်။',
            'to.integer' => 'ပြီးဆုံးမည့်နှစ်သည် ဂဏန်းဖြစ်ရပါမည်။',
            'to.digits' => 'ပြီးဆုံးမည့်နှစ်သည် ဂဏန်း 4 လုံးဖြစ်ရပါမည်။',

            'name.required' => 'စီမံကိန်းအမည်ထည့်ရန်လိုအပ်ပါသည်။',
            'location.required' => 'တည်နေရာထည့်ရန်လိုအပ်ပါသည်။',
            'start_month.required' => 'စတင်မည့်ကာလထည့်ရန်လိုအပ်ပါသည်။',
            'end_month.required' => 'ပြီးဆုံးမည့်ကာလထည့်ရန်လိုအပ်ပါသည်။',
            'department.required' => 'ဆောင်ရွက်မည့်ဌာန/အဖွဲ့အစည်းထည့်ရန်လိုအပ်ပါသည်။',

            'total_investment.required' => 'စုစုပေါင်းရင်းနှီးမြှုပ်နှံမည့်ငွေထည့်ရန်လိုအပ်ပါသည်။',
            'total_investment.numeric' => 'စုစုပေါင်းရင်းနှီးမြှုပ်နှံမည့်ငွေကို နံပါတ်ဖြင့်ထည့်ရန်လိုအပ်ပါသည်။',

            'operation_year.required' => 'ဆောင်ရွက်မည့်နှစ်ထည့်ရန်လိုအပ်ပါသည်။',
            'operation_year.integer' => 'ဆောင်ရွက်မည့်နှစ်သည် ဂဏန်းဖြစ်ရပါမည်။',
            'operation_year.digits' => 'ဆောင်ရွက်မည့်နှစ်သည် ဂဏန်း 4 လုံးဖြစ်ရပါမည်။',
            'operation_year.between' => 'ဆောင်ရွက်မည့်နှစ်သည် 1901 မှ 2155 အတွင်းရှိရပါမည်။',

            'regional_budget.required' => 'တိုင်းဒေသကြီးဘတ်ဂျက်ထည့်ရန်လိုအပ်ပါသည်။',
            'regional_budget.numeric' => 'တိုင်းဒေသကြီးဘတ်ဂျက်ကို နံပါတ်ဖြင့်ထည့်ရန်လိုအပ်ပါသည်။',

            'tender_price.required' => 'တင်ဒါအောင်မြင်သည့်စျေးနှုန်းထည့်ရန်လိုအပ်ပါသည်။',
            'tender_price.numeric' => 'တင်ဒါအောင်မြင်သည့်စျေးနှုန်းကို နံပါတ်ဖြင့်ထည့်ရန်လိုအပ်ပါသည်။',
        ]);

        $report->update($validated);

        return response()->json(['success' => 'စီမံကိန်းကို အောင်မြင်စွာ update ပြီးပါပြီ']);
    }
}
