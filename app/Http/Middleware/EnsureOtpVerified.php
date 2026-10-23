<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOtpVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->get('otp_verified')) {
            return redirect()
                ->route('otp.request')
                ->with('error', 'Please verify your email address to access this page.');
        }

        return $next($request);
    }
}
