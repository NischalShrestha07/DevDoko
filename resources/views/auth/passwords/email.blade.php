@extends('layouts.guest')

@section('title', 'Reset Password - DevDoko')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h2 class="fw-bold">
                        <img src="{{ asset('assets/devdoko.png') }}" width="50" height="50"
                            style="border: 1px solid black;" class="rounded-circle" alt="DevDoko"> DevDoko
                    </h2>
                    <p class="text-muted">Reset your password</p>
                </div>

                @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email') }}" required autofocus>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg">Send Password Reset Link</button>
                    </div>

                    <div class="text-center">
                        <p class="mb-0">
                            Remember your password?
                            <a href="{{ route('login') }}" class="text-decoration-none">Log in</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
