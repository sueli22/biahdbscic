<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
}
