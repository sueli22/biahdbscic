<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserLoginRequest;

class UserController extends Controller
{
    public function login(UserLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->isSuperUser()) {
                return redirect()->route('dashboard');
            } else {
                return redirect()->route('employee.dashboard');
            }
        }
        return back()
            ->withErrors(['email' => 'အီးမေးလ် သို့မဟုတ် စကားဝှက် မမှန်ပါ'])
            ->onlyInput('email');
    }

    public function loginForm()
    {
        return view('auth.login');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
