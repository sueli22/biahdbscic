<?php

namespace App\Http\Controllers;

use App\Models\Web;
use Illuminate\Http\Request;

class WebController extends Controller
{
    public function index()
    {
        $webs = Web::all();
        return view('admin.web.index', compact('webs'));
    }

    public function update(Request $request, $id)
    {

        $validated = $request->validate([
            'color' => 'required|string|max:20',
            'logoimg' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'bgimg' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'footer' => 'required|string|max:1000',
        ], [
            'color.required' => 'အရောင်သည် လိုအပ်သော အချက်အလက် ဖြစ်သည်။',
            'color.string' => 'အရောင်သည် စာသားအမျိုးအစား ဖြစ်ရမည်။',
            'color.max' => 'အရောင်သည် အများဆုံး စာလုံး 20 လုံးသာ ရနိုင်ပါသည်။',

            'logoimg.required' => 'လိုဂို သည် လိုအပ်သော အချက်အလက် ဖြစ်သည်။',
            'logoimg.image' => 'လိုဂိုသည် ပုံဖိုင်သာ ဖြစ်ရမည်။',
            'logoimg.mimes' => 'လိုဂိုသည် jpeg, png, jpg, gif, svg ဖိုင်မျိုးသာ ဖြစ်ရမည်။',
            'logoimg.max' => 'လိုဂိုသည် အများဆုံး 2MB အထိသာ ဖြစ်နိုင်ပါသည်။',

            'bgimg.required' => 'နောက်ခံပုံသည် လိုအပ်သော အချက်အလက် ဖြစ်သည်။',
            'bgimg.image' => 'နောက်ခံပုံသည် ပုံဖိုင်သာ ဖြစ်ရမည်။',
            'bgimg.mimes' => 'နောက်ခံပုံသည် jpeg, png, jpg, gif, svg ဖိုင်မျိုးသာ ဖြစ်ရမည်။',
            'bgimg.max' => 'နောက်ခံပုံသည် အများဆုံး 2MB အထိသာ ဖြစ်နိုင်ပါသည်။',

            'footer.required' => 'အောက်ခြေစာသားသည် လိုအပ်သော အချက်အလက် ဖြစ်သည်။',
            'footer.string' => 'အောက်ခြေစာသား သည် စာသားအမျိုးအစား ဖြစ်ရမည်။',
            'footer.max' => 'အောက်ခြေစာသား သည် အများဆုံး စာလုံး 1000 လုံးသာ ရနိုင်ပါသည်။',
        ]);


        $web = Web::findOrFail($id);

        if ($request->hasFile('logoimg')) {
            $logoName = time() . '_' . $request->file('logoimg')->getClientOriginalName();
            $request->file('logoimg')->move(public_path('logo'), $logoName);
            $validated['logoimg'] = $logoName;
        }

        if ($request->hasFile('bgimg')) {
            $bgName = time() . '_' . $request->file('bgimg')->getClientOriginalName();
            $request->file('bgimg')->move(public_path('bg'), $bgName);
            $validated['bgimg'] = $bgName;
        }

        $web->update($validated);
        return response()->json($web);
    }
}
