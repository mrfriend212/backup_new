<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if(auth()->user() !== null) {
            abort('404');
        }else {
            return view('layouts.auth.login');
        }
    }

    public function login(Request $request)
    {
        $userInputs = $request->all();

        // 1. Validate inputs
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        // 2. Add status condition to credentials
        $credentials['status'] = 'active';

        // 3. Attempt to authenticate
        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/');
        } else {
            // Check if user exists but is inactive
            $user = \App\Models\User::where('username', $request->username)->first();
            
            if ($user && $user->status === 'deactive') {
                return back()->withErrors([
                    'message' => 'کاربری غیر فعال است. با پشتیبانی تماس بگیرید.',
                ])->onlyInput('username');
            }
            
            return back()->withErrors([
                'message' => 'نام کاربری یا رمز عبور اشتباه است.',
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
}
