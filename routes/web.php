<?php

use Illuminate\Support\Facades\Route;




/********  Authentication  *********/
Route::middleware('auth')->get('/', function(){
    $user_type = auth()->user()->user_type;
    return view('layouts.panel.master.app', ['iframe_route' => 'panel.'.$user_type.'.dashboard']);
})->name('home');

Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLogin'])->name('showLogin');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::get('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');


/********  Admin Routes  *********/
Route::middleware(['auth','not_direct_access_to_page'])->prefix('panel/admin')->name('panel.admin.')->group(function () {
    Route::get('/dashboard', function(){
        return view('dashboard');
    })->name('dashboard');
});


/********  Manager Routes  *********/
Route::middleware(['auth','not_direct_access_to_page'])->prefix('panel/manager')->name('panel.manager.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


/********  User Routes  *********/
Route::middleware(['auth','not_direct_access_to_page'])->prefix('panel/user')->name('panel.user.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});