<?php

namespace App\Http\Controllers;

use App\Helpers\OtpHelper;
use App\Mail\SendOTPEmail;
use App\Models\OtpCode;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class OtpController extends Controller
{
    private const EXPIRY_MINUTES = 5;

    private const MAX_ATTEMPTS = 5;

    public function showRequest(): View
    {
        return view('request-otp');
    }

    public function send(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        $email = $validated['email'];
        $otp = (string) OtpHelper::generateOTP();

        OtpCode::updateOrCreate(
            ['email' => $email],
            [
                'code' => Hash::make($otp),
                'expires_at' => now()->addMinutes(self::EXPIRY_MINUTES),
                'attempts' => 0,
            ],
        );

        try {
            Mail::to($email)->send(new SendOTPEmail($otp));
        } catch (Throwable $e) {
            report($e);
            OtpCode::where('email', $email)->delete();

            return back()
                ->withInput()
                ->with('error', 'We could not send the verification email. Please try again.');
        }

        $request->session()->put('otp_email', $email);
        $request->session()->forget(['otp_verified', 'otp_verified_email']);

        return redirect()
            ->route('otp.verify')
            ->with('success', 'A verification code has been sent to your email.');
    }

    public function showVerify(Request $request): View|RedirectResponse
    {
        $email = $request->session()->get('otp_email');

        if (! $email) {
            return redirect()
                ->route('otp.request')
                ->with('error', 'Please request a verification code first.');
        }

        return view('verify-otp', ['email' => $email]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $email = $request->session()->get('otp_email');

        if (! $email) {
            return redirect()
                ->route('otp.request')
                ->with('error', 'Please request a verification code first.');
        }

        $validated = $request->validate(
            ['otp' => ['required', 'regex:/^\d{6}$/']],
            [
                'otp.required' => 'Please enter the OTP.',
                'otp.regex' => 'The OTP must contain exactly six digits.',
            ],
        );

        $record = OtpCode::where('email', $email)->first();

        if (! $record || $record->expires_at->isPast()) {
            $record?->delete();

            return back()->with('error', 'The OTP is incorrect or has expired.');
        }

        if (! Hash::check($validated['otp'], $record->code)) {
            $record->increment('attempts');

            if ($record->attempts >= self::MAX_ATTEMPTS) {
                $record->delete();
            }

            return back()->with('error', 'The OTP is incorrect or has expired.');
        }

        $record->delete();

        $request->session()->regenerate();
        $request->session()->put([
            'otp_verified' => true,
            'otp_verified_email' => $email,
        ]);
        $request->session()->forget('otp_email');

        return redirect()
            ->route('home')
            ->with('success', 'OTP verified successfully!');
    }
}
