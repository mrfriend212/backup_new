<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;




/********  Authentication  *********/
Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('showLogin');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::get('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');
Route::get('/', function () {
    if(Auth::check() === false) {
        return to_route('showLogin');
    } else {
        if(auth()->user()->user_type === 'admin') {
            return redirect()->route('panel.admin.dashboard');
        } elseif(auth()->user()->user_type === 'manager') {
            return redirect()->route('panel.manager.dashboard');
        } elseif(auth()->user()->user_type === 'user') {
            return redirect()->route('panel.user.dashboard');
        } else {
            return to_route('showLogin');
        }
    }
});


/********  Admin Routes  *********/
Route::middleware(['auth'])->prefix('panel/admin')->name('panel.admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AuthController::class, 'showDashboard'])->name('showDashboard');
});


/********  Manager Routes  *********/
Route::middleware(['auth'])->prefix('panel/manager')->name('panel.manager.')->group(function () {
    Route::get('/dashboard', function () {
        return view('manager');
    })->name('dashboard');
});


/********  User Routes  *********/
Route::middleware(['auth'])->prefix('panel/user')->name('panel.user.')->group(function () {
    Route::get('/dashboard', function () {
        return view('user');
    })->name('dashboard');
});