<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Change if you want to restrict access
    }

    public function rules()
    {
        $staffId = $this->route('id'); // Get current user ID from route

        return [
            'eid' => 'required|string|max:50|unique:users,eid,' . $staffId,
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $staffId,
            'position_id' => 'required|exists:positions,position_id',
            'dob' => 'required|date|before:2000',
            'currentaddress' => 'nullable|string|max:255',
            'phno' => 'required|regex:/^09\d{7,9}$/|max:11',
            'department' => 'required|string|max:100',
            'gender' => 'required|in:0,1,2',
            'married_status' => 'required|boolean',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'required|string|min:8',
        ];
    }

    public function messages()
    {
        return [
            'eid.required' => 'ဝန်ထမ်းနံပါတ်ထည့်ရန် လိုအပ်သည်။',
            'eid.unique' => 'ဝန်ထမ်းနံပါတ် သီးခြားဖြစ်ရမည်။',
            'eid.string' => 'ဝန်ထမ်းနံပါတ်သည် စာသားဖြစ်ရမည်။',
            'eid.max' => 'ဝန်ထမ်းနံပါတ်သည် ၅၀ စာလုံးအတွင်း ဖြစ်ရမည်။',

            'name.required' => 'အမည်ထည့်ရန် လိုအပ်သည်။',
            'name.string' => 'အမည်သည် စာသားဖြစ်ရမည်။',
            'name.max' => 'အမည်သည် ၂၅၅ စာလုံးအတွင်း ဖြစ်ရမည်။',

            'email.required' => 'အီးမေးလ်ထည့်ရန် လိုအပ်သည်။',
            'email.email' => 'အီးမေးလ်ပုံစံ မမှန်ကန်ပါ။',
            'email.unique' => 'အီးမေးလ် သီးခြားဖြစ်ရမည်။',

            'position_id.required' => 'ရာထူးရွေးရန် လိုအပ်သည်။',
            'position_id.exists' => 'ရာထူးရွေးချယ်မှု မမှန်ကန်ပါ။',

            'dob.required' => 'မွေးသက္ကရာဇ်ထည့်ရန် လိုအပ်သည်။',
            'dob.date' => 'မွေးသက္ကရာဇ်သည် မှန်ကန်သော ရက်စွဲဖြစ်ရမည်။',
            'dob.before' => 'မွေးသက္ကရာဇ်သည် ၂၀၀၀ မတိုင်မီ ဖြစ်ရမည်။',

            'currentaddress.string' => 'လိပ်စာသည် စာသားဖြစ်ရမည်။',
            'currentaddress.max' => 'လိပ်စာသည် ၂၅၅ စာလုံးအတွင်း ဖြစ်ရမည်။',
            'phno.required' => 'ဖုန်းနံပါတ်ထည့်ရန် လိုအပ်သည်။',
            'phno.regex' => 'ဖုန်းနံပါတ်သည် 09XXXXXXXXX (09 နံပါတ်ဖြင့် စတင်ပြီး ၁၁ လုံးရှိရမည်) ။',
            'phno.string' => 'ဖုန်းနံပါတ်သည် စာသားဖြစ်ရမည်။',
            'phno.max' => 'ဖုန်းနံပါတ်သည် 11 စာလုံးအတွင်း ဖြစ်ရမည်။',

            'department.required' => 'ဌာနထည့်ရန် လိုအပ်သည်။',
            'department.string' => 'ဌာနသည် စာသားဖြစ်ရမည်။',
            'department.max' => 'ဌာနသည် ၁၀၀ စာလုံးအတွင်း ဖြစ်ရမည်။',

            'gender.required' => 'ကျား/မ/အခြား သတ်မှတ်ချက်ထည့်ရန် လိုအပ်သည်။',
            'gender.in' => 'ကျား/မ/အခြား သတ်မှတ်ချက် မမှန်ကန်ပါ။',

            'married_status.required' => 'လက်ထပ်မှု အချက်အလက်ထည့်ရန် လိုအပ်သည်။',
            'married_status.boolean' => 'လက်ထပ်မှု အချက်အလက် မမှန်ကန်ပါ။',

            'image.image' => 'ဓာတ်ပုံ ဖိုင်သာဖြစ်ရမည်။',
            'image.mimes' => 'ဓာတ်ပုံအမျိုးအစား မမှန်ပါ (jpeg, png, jpg, gif).',
            'image.max' => 'ဓာတ်ပုံ အရွယ်အစား 2MB အတွင်း ဖြစ်ရမည်။',

            'password.required' => 'စကားဝှက်ထည့်ရန် လိုအပ်သည်။',
            'password.string' => 'စကားဝှက်သည် စာသားဖြစ်ရမည်။',
            'password.min' => 'စကားဝှက်သည် အနည်းဆုံး ၈ လုံးရှိရမည်။',
        ];
    }
}
