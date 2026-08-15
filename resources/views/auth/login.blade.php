@extends('layouts.guest')

@section('title', 'Login - DevDoko')

@section('content')
<div class="auth-form-header">
    <img src="{{ asset('assets/devdoko.png') }}" alt="DevDoko" class="logo-mobile">
    <h1>Welcome back</h1>
    <p>Sign in to your DevDoko account</p>
</div>

<form method="POST" action="{{ route('login') }}" id="loginForm">
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

    <div class="auth-input-group">
        <label for="password">Password</label>
        <div class="auth-input-wrapper">
            <i class="bi bi-lock input-icon"></i>
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                id="password" name="password" placeholder="Enter your password" required>
            <button type="button" class="auth-password-toggle" onclick="togglePassword('password', this)" aria-label="Toggle password visibility">
                <i class="bi bi-eye"></i>
            </button>
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
        <label class="auth-check-label">
            <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
            Remember me
        </label>
        @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}" style="font-size: 0.875rem; color: #667eea; text-decoration: none; font-weight: 500;">
            Forgot password?
        </a>
        @endif
    </div>

    <button type="submit" class="auth-submit-btn">
        Sign In
    </button>
</form>

<div style="display: flex; align-items: center; gap: 0.75rem; margin: 1.25rem 0; color: #8b949e; font-size: 0.8125rem;">
    <div style="flex: 1; height: 1px; background: #dee2e6;"></div>
    <span>or</span>
    <div style="flex: 1; height: 1px; background: #dee2e6;"></div>
</div>

<a href="{{ route('auth.github.redirect') }}" class="auth-submit-btn" style="display: flex; align-items: center; justify-content: center; gap: 0.5rem; background: #24292f; text-decoration: none;">
    <i class="bi bi-github"></i> Continue with GitHub
</a>

<p class="auth-footer-text">
    Don't have an account?
    <a href="{{ route('register') }}">Create one free</a>
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
