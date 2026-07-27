@extends('layouts.app')

@section('title', 'Apply - ' . $job->title . ' | DevDoko')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('jobs.show', $job) }}" class="text-decoration-none text-dark d-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i>
            <span>Back to Job</span>
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-lg-5">
                    <h4 class="fw-bold mb-1">
                        <i class="bi bi-send text-primary me-2"></i>
                        Apply for {{ $job->title }}
                    </h4>
                    <p class="text-muted mb-4">at {{ $job->company_name }}</p>

                    @if($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                                <span class="fw-semibold">Please fix the following errors:</span>
                            </div>
                            <ul class="mb-0 small">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('jobs.apply.store', $job) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="cover_letter" class="form-label fw-semibold">Cover Letter <span class="text-danger">*</span></label>
                            <textarea name="cover_letter" id="cover_letter" rows="8"
                                class="form-control @error('cover_letter') is-invalid @enderror"
                                placeholder="Tell the employer why you're a great fit for this role...">{{ old('cover_letter') }}</textarea>
                            @error('cover_letter')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Minimum 20 characters</div>
                        </div>

                        <div class="mb-4">
                            <label for="resume" class="form-label fw-semibold">Resume <span class="text-danger">*</span></label>
                            <input type="file" name="resume" id="resume"
                                class="form-control @error('resume') is-invalid @enderror"
                                accept=".pdf,.doc,.docx">
                            @error('resume')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">PDF, DOC, or DOCX — max 5MB</div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill px-5">
                                <i class="bi bi-check-lg me-2"></i>Submit Application
                            </button>
                            <a href="{{ route('jobs.show', $job) }}" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
