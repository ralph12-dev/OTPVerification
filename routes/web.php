<?php

use App\Http\Controllers\OtpController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('otp.request'));

Route::get('/request-otp', [OtpController::class, 'showRequest'])->name('otp.request');
Route::post('/send-otp', [OtpController::class, 'send'])
    ->middleware('throttle:5,1')
    ->name('otp.send');

Route::get('/verify-otp', [OtpController::class, 'showVerify'])->name('otp.verify');
Route::post('/verify-otp', [OtpController::class, 'verify'])
    ->middleware('throttle:10,1')
    ->name('otp.verify.submit');

Route::get('/home', fn () => view('home', ['email' => session('otp_verified_email')]))
    ->middleware('otp.verified')
    ->name('home');
