@extends('layouts.app')

@section('title', 'Post a Job - DevDoko')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('jobs.index') }}" class="text-decoration-none app-text-primary d-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i>
            <span>Back to Jobs</span>
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    <h4 class="fw-bold mb-1">
                        <i class="bi bi-plus-circle text-primary me-2"></i>
                        Post a Job
                    </h4>
                    <p class="app-text-muted mb-4">Fill in the details to attract the best talent</p>

                    @include('jobs.partials.form', [
                        'job' => null,
                        'route' => route('jobs.store'),
                        'method' => 'POST',
                        'buttonText' => 'Post Job'
                    ])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
