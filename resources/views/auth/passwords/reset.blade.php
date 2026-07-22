@extends('layouts.guest')

@section('title', 'Reset Password - DevDoko')

@section('content')
<div class="auth-form-header">
    <img src="{{ asset('assets/devdoko.png') }}" alt="DevDoko" class="logo-mobile">
    <h1>Set new password</h1>
    <p>Choose a strong password for your account</p>
</div>

<form method="POST" action="{{ route('password.update') }}">
    @csrf

    <input type="hidden" name="token" value="{{ $token }}">

    <div class="auth-input-group">
        <label for="email">Email address</label>
        <div class="auth-input-wrapper">
            <i class="bi bi-envelope input-icon"></i>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                name="email" value="{{ old('email', $email ?? '') }}" placeholder="you@example.com" required autofocus>
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="auth-input-group">
        <label for="password">New password</label>
        <div class="auth-input-wrapper">
            <i class="bi bi-lock input-icon"></i>
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                id="password" name="password" placeholder="Enter new password" required>
            <button type="button" class="auth-password-toggle" onclick="togglePassword('password', this)" aria-label="Toggle password visibility">
                <i class="bi bi-eye"></i>
            </button>
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="auth-input-group">
        <label for="password_confirmation">Confirm new password</label>
        <div class="auth-input-wrapper">
            <i class="bi bi-lock-fill input-icon"></i>
            <input type="password" class="form-control" id="password_confirmation"
                name="password_confirmation" placeholder="Re-enter new password" required>
            <button type="button" class="auth-password-toggle" onclick="togglePassword('password_confirmation', this)" aria-label="Toggle password visibility">
                <i class="bi bi-eye"></i>
            </button>
        </div>
    </div>

    <button type="submit" class="auth-submit-btn" style="margin-top: 0.5rem;">
        Reset Password
    </button>
</form>

<p class="auth-footer-text">
    Remember your password?
    <a href="{{ route('login') }}">Back to sign in</a>
</p>

<script>
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
</script>
@endsection
