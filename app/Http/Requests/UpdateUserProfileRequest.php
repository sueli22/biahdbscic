<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $userId = $this->user()->id; // get currently authenticated user's id

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'dob' => 'required|date',
            'currentaddress' => 'required|string|max:255',
            'phno' => 'required|string|max:15',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'department' => 'nullable|string|max:255',
            'married_status' => 'nullable|in:0,1',
            'gender' => 'nullable|in:0,1',
        ];
    }

    public function messages()
    {
        return [
            'name.required'           => 'နာမည် ထည့်ရန်လိုအပ်သည်။',
            'name.string'             => 'နာမည်သည် စာသားသာဖြစ်ရမည်။',
            'name.max'                => 'နာမည်သည် အများဆုံး 255 အက္ခရာသာ ခွင့်ပြုသည်။',

            'email.required'          => 'အီးမေးလ် ထည့်ရန်လိုအပ်သည်။',
            'email.email'             => 'အီးမေးလ် ပုံစံမမှန်ပါ။',
            'email.max'               => 'အီးမေးလ်သည် အများဆုံး 255 အက္ခရာသာ ခွင့်ပြုသည်။',
            'email.unique'            => 'ဤအီးမေးလ်အား တခြားသူအသုံးပြုပြီးသား ဖြစ်သည်။',

            'dob.required'            => 'မွေးသက္ကရာဇ် ထည့်ရန်လိုအပ်သည်။',
            'dob.date'                => 'မွေးသက္ကရာဇ်သည် ရက်စွဲပုံစံဖြစ်ရမည်။',

            'currentaddress.required' => 'လက်ရှိနေရပ်လိပ်စာ ထည့်ရန်လိုအပ်သည်။',
            'currentaddress.string'   => 'လိပ်စာသည် စာသားသာဖြစ်ရမည်။',
            'currentaddress.max'      => 'လိပ်စာသည် အများဆုံး 255 အက္ခရာသာ ခွင့်ပြုသည်။',

            'phno.required'           => 'ဖုန်းနံပါတ် ထည့်ရန်လိုအပ်သည်။',
            'phno.string'             => 'ဖုန်းနံပါတ်သည် စာသားသာဖြစ်ရမည်။',
            'phno.max'                => 'ဖုန်းနံပါတ်သည် အများဆုံး 15 လုံးသာ ခွင့်ပြုသည်။',

            'image.image'             => 'ပုံဖိုင်သာ တင်နိုင်သည်။',
            'image.mimes'             => 'ပုံသည် jpeg, png, jpg, gif ဖြစ်ရမည်။',
            'image.max'               => 'ပုံဖိုင် အရွယ်အစားသည် 2MB ထက် မကျော်ရ။',

            'department.string'       => 'ဌာနအမည်သည် စာသားသာဖြစ်ရမည်။',
            'department.max'          => 'ဌာနအမည်သည် အများဆုံး 255 အက္ခရာသာ ခွင့်ပြုသည်။',

            'married_status.in'       => 'လက်ထပ်မှုအခြေအနေ သည် မှန်ကန်သော တန်ဖိုး ဖြစ်ရမည်။',
            'gender.in'               => 'ကျား/မ သည် မှန်ကန်သော တန်ဖိုး ဖြစ်ရမည်။',
        ];
    }
}
