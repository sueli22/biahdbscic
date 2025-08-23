<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Web;
use App\Http\Requests\UserLoginRequest;

class UserController extends Controller
{
    public function login(UserLoginRequest $request)
    {
        $web = Web::first();
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->isSuperUser()) {
                return redirect()->route('dashboard');
            } else {
                return redirect()->route('employee.profile.show');
            }
        }
        return back()
            ->withErrors(['email' => 'အီးမေးလ် သို့မဟုတ် စကားဝှက် မမှန်ပါ'])
            ->onlyInput('email');
    }

    public function loginForm()
    {
        $web = Web::first();
        return view('auth.login', compact('web'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function getUserSalary(User $user)
    {
        $salary = $user->position ? $user->position->salary : 0;

        return response()->json(['salary' => $salary]);
    }

}
