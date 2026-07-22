@extends('layouts.guest')

@section('title', 'Register - DevDoko')

@section('content')
<div class="auth-form-header">
    <img src="{{ asset('assets/devdoko.png') }}" alt="DevDoko" class="logo-mobile">
    <h1>Create your account</h1>
    <p>Join the developer community today</p>
</div>

<form method="POST" action="{{ route('register') }}" id="registerForm">
    @csrf

    <div class="auth-input-group">
        <label for="name">Full name</label>
        <div class="auth-input-wrapper">
            <i class="bi bi-person input-icon"></i>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                name="name" value="{{ old('name') }}" placeholder="John Doe" required autofocus>
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="auth-input-group">
        <label for="email">Email address</label>
        <div class="auth-input-wrapper">
            <i class="bi bi-envelope input-icon"></i>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                name="email" value="{{ old('email') }}" placeholder="you@example.com" required>
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="auth-input-group">
        <label for="username">Username</label>
        <div class="auth-input-wrapper">
            <i class="bi bi-at input-icon"></i>
            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username"
                name="username" value="{{ old('username') }}" placeholder="johndoe" required>
            @error('username')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <p class="auth-helper-text">This will be your public profile URL: devdoko.com/<strong>@username</strong></p>
    </div>

    <div class="auth-input-group">
        <label for="password">Password</label>
        <div class="auth-input-wrapper">
            <i class="bi bi-lock input-icon"></i>
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                id="password" name="password" placeholder="Create a strong password" required>
            <button type="button" class="auth-password-toggle" onclick="togglePassword('password', this)" aria-label="Toggle password visibility">
                <i class="bi bi-eye"></i>
            </button>
            @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="auth-input-group">
        <label for="password_confirmation">Confirm password</label>
        <div class="auth-input-wrapper">
            <i class="bi bi-lock-fill input-icon"></i>
            <input type="password" class="form-control" id="password_confirmation"
                name="password_confirmation" placeholder="Re-enter your password" required>
            <button type="button" class="auth-password-toggle" onclick="togglePassword('password_confirmation', this)" aria-label="Toggle password visibility">
                <i class="bi bi-eye"></i>
            </button>
        </div>
    </div>

    <button type="submit" class="auth-submit-btn" style="margin-top: 0.5rem;">
        Create Account
    </button>
</form>

<p class="auth-footer-text">
    Already have an account?
    <a href="{{ route('login') }}">Sign in</a>
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
