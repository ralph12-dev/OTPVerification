@extends('layouts.app')

@section('title', 'Request Verification Code')

@section('content')
    <h1>Email Verification</h1>
    <p class="muted">
        Enter your registered email address and we will send you a six-digit verification code.
    </p>

    @if (session('error'))
        <div class="alert alert-error" role="alert">{{ session('error') }}</div>
    @endif

    @if (session('success'))
        <div class="alert alert-success" role="status">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('otp.send') }}" novalidate>
        @csrf

        <div class="field">
            <label for="email">Email address</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                autocomplete="email"
                maxlength="255"
                required
                autofocus
                @error('email') aria-invalid="true" aria-describedby="email-error" @enderror
            >
            @error('email')
                <p class="field-error" id="email-error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit">Send verification code</button>
    </form>

    <p class="small muted">
        Already requested a code? <a href="{{ route('otp.verify') }}">Verify it here</a>.
    </p>
@endsection
