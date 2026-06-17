<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('layouts.auth.login');
    }

    public function login(Request $request)
    {
        $userInputs = $request->all();

        // 1. Validate inputs
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        // 2. Attempt to authenticate        
        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();

            return redirect('/');
        } else {
            return back()->withErrors([
                'username' => 'نام کاربری یا رمز عبور اشتباه است.',
            ])->onlyInput('username');
        }
    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('showLogin');
    }

    public function showDashboard() {
        return view('dashboard');
    }
}
