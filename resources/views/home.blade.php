@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <span class="badge">Verified</span>
    <h1>OTP Verified Successfully!</h1>
    <p class="muted">
        @if ($email)
            <span class="email">{{ $email }}</span> has been verified. You now have access to the protected home page.
        @else
            Your email address has been verified. You now have access to the protected home page.
        @endif
    </p>

    @if (session('success'))
        <div class="alert alert-success" role="status">{{ session('success') }}</div>
    @endif

    <p class="small muted">
        <a href="{{ route('otp.request') }}">Verify another email address</a>
    </p>
@endsection
