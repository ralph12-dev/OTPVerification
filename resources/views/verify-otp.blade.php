@extends('layouts.app')

@section('title', 'OTP Verification')

@section('content')
    <h1>OTP Verification</h1>
    <p class="muted">
        Check your registered email address <strong class="email">{{ $email }}</strong> and enter the
        six-digit code we sent you. The code expires in 5 minutes.
    </p>

    @if (session('error'))
        <div class="alert alert-error" role="alert">{{ session('error') }}</div>
    @endif

    @if (session('success'))
        <div class="alert alert-success" role="status">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('otp.verify.submit') }}" novalidate>
        @csrf

        <div class="field">
            <label for="otp">Verification code</label>
            <input
                id="otp"
                type="text"
                name="otp"
                class="otp-input"
                inputmode="numeric"
                pattern="[0-9]{6}"
                maxlength="6"
                autocomplete="one-time-code"
                value="{{ old('otp') }}"
                required
                autofocus
                @error('otp') aria-invalid="true" aria-describedby="otp-error" @enderror
            >
            @error('otp')
                <p class="field-error" id="otp-error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit">Verify OTP</button>
    </form>

    <p class="small muted">
        Did not receive the code? <a href="{{ route('otp.request') }}">Request a new code</a>.
    </p>
@endsection
