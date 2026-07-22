@extends('layouts.guest')

@section('title', 'Reset Password - DevDoko')

@section('content')
<div class="auth-form-header">
    <img src="{{ asset('assets/devdoko.png') }}" alt="DevDoko" class="logo-mobile">
    <h1>Reset your password</h1>
    <p>Enter your email and we'll send you a reset link</p>
</div>

@if (session('status'))
<div class="auth-alert" role="alert" style="background: #dcfce7; color: #166534; border: 1px solid #86efac; margin-bottom: 1.25rem;">
    <i class="bi bi-check-circle-fill"></i>
    {{ session('status') }}
</div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <div class="auth-input-group">
        <label for="email">Email address</label>
        <div class="auth-input-wrapper">
            <i class="bi bi-envelope input-icon"></i>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <button type="submit" class="auth-submit-btn" style="margin-top: 0.5rem;">
        <i class="bi bi-send me-2"></i>Send Reset Link
    </button>
</form>

<p class="auth-footer-text">
    Remember your password?
    <a href="{{ route('login') }}">Back to sign in</a>
</p>
@endsection
