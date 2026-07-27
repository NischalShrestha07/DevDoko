@extends('layouts.app')

@section('title', $job->title . ' - ' . $job->company_name . ' | DevDoko')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <a href="{{ route('jobs.index') }}"
            class="text-decoration-none text-dark d-flex align-items-center gap-2">
            <i class="bi bi-arrow-left"></i>
            <span>Back to Jobs</span>
        </a>

        @auth
            @if(auth()->id() === $job->user_id)
                <div class="d-flex gap-2">
                    <a href="{{ route('jobs.edit', $job) }}"
                        class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                        <i class="bi bi-pencil"></i>
                        <span class="d-none d-sm-inline">Edit</span>
                    </a>
                    <form action="{{ route('jobs.destroy', $job) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1"
                            onclick="if(confirm('Delete this job listing?')){this.closest('form').submit()}">
                            <i class="bi bi-trash"></i>
                            <span class="d-none d-sm-inline">Delete</span>
                        </button>
                    </form>
                </div>
            @endif
        @endauth
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4 p-lg-5">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <span class="badge bg-primary rounded-pill mb-2">{{ $job->type }}</span>
                            @if($job->is_featured)
                                <span class="badge bg-warning text-dark rounded-pill mb-2 ms-2">
                                    <i class="bi bi-star-fill me-1"></i>Featured
                                </span>
                            @endif
                        </div>
                        <small class="text-muted">
                            <i class="bi bi-eye me-1"></i>{{ $job->views_count }} views
                        </small>
                    </div>

                    <h2 class="fw-bold mb-1">{{ $job->title }}</h2>
                    <p class="h5 text-muted mb-3">{{ $job->company_name }}</p>

                    <div class="d-flex flex-wrap gap-3 mb-4">
                        @if($job->location)
                            <span class="text-muted small">
                                <i class="bi bi-geo-alt me-1"></i>{{ $job->location }}
                            </span>
                        @endif
                        <span class="text-muted small">
                            <i class="bi bi-briefcase me-1"></i>{{ $job->location_type }}
                        </span>
                        <span class="text-muted small">
                            <i class="bi bi-bar-chart me-1"></i>{{ $job->experience_level }}
                        </span>
                        @if($job->salary_min || $job->salary_max)
                            <span class="text-muted small">
                                <i class="bi bi-currency-exchange me-1"></i>
                                {{ $job->salary_currency ?? 'NPR' }}
                                @if($job->salary_min){{ number_format($job->salary_min) }}@endif
                                @if($job->salary_min && $job->salary_max) - @endif
                                @if($job->salary_max){{ number_format($job->salary_max) }}@endif
                            </span>
                        @endif
                        <span class="text-muted small">
                            <i class="bi bi-clock me-1"></i>Posted {{ $job->created_at->diffForHumans() }}
                        </span>
                    </div>

                    @if(is_array($job->required_skills) && count($job->required_skills) > 0)
                        <div class="mb-4">
                            <h6 class="fw-semibold mb-2">Required Skills</h6>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($job->required_skills as $skill)
                                    <span class="badge bg-light text-dark border px-3 py-2">{{ $skill }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($job->expires_at)
                        <div class="mb-4">
                            <small class="text-muted">
                                <i class="bi bi-calendar-event me-1"></i>
                                Applications close {{ $job->expires_at->format('M d, Y') }}
                            </small>
                        </div>
                    @endif

                    <hr>

                    <div class="job-description">
                        {!! nl2br(e($job->description)) !!}
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-3">
                        <i class="bi bi-send me-2"></i>Interested in this position?
                    </h6>

                    @auth
                        @if(auth()->id() === $job->user_id)
                            <p class="text-muted small mb-3">This is your job listing.</p>
                            <a href="{{ route('jobs.applicants', $job) }}" class="btn btn-primary rounded-pill px-4">
                                <i class="bi bi-people me-2"></i>View Applicants ({{ $job->applications_count }})
                            </a>
                        @else
                            <div class="d-flex flex-wrap gap-2">
                                @if($userApplication)
                                    <span class="btn btn-success rounded-pill px-4 disabled">
                                        <i class="bi bi-check-circle me-2"></i>Applied &middot; {{ ucfirst($userApplication->status) }}
                                    </span>
                                @else
                                    <a href="{{ route('jobs.apply.create', $job) }}" class="btn btn-primary rounded-pill px-4">
                                        <i class="bi bi-send me-2"></i>Apply Now
                                    </a>
                                @endif

                                @if($job->company_website)
                                    <a href="{{ $job->company_website }}" target="_blank" rel="noopener noreferrer"
                                        class="btn btn-outline-secondary rounded-pill px-4">
                                        <i class="bi bi-box-arrow-up-right me-2"></i>Company Site
                                    </a>
                                @endif

                                <form action="{{ $isSaved ? route('jobs.unsave', $job) : route('jobs.save', $job) }}" method="POST" class="job-save-form d-inline">
                                    @csrf
                                    @if($isSaved) @method('DELETE') @endif
                                    <button type="submit" class="btn btn-outline-secondary rounded-pill px-4">
                                        <i class="bi {{ $isSaved ? 'bi-bookmark-fill' : 'bi-bookmark' }} me-2"></i><span>{{ $isSaved ? 'Saved' : 'Save Job' }}</span>
                                    </button>
                                </form>
                            </div>
                        @endif
                    @else
                        <p class="text-muted small mb-3">Log in to apply for this position.</p>
                        <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login to Apply
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-semibold mb-0">
                        <i class="bi bi-building me-2"></i>Company
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        @if($job->company_logo)
                            <img src="{{ Storage::url($job->company_logo) }}" alt="{{ $job->company_name }}"
                                class="rounded" style="width: 48px; height: 48px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-building text-muted fs-5"></i>
                            </div>
                        @endif
                        <div>
                            <h6 class="fw-semibold mb-0">{{ $job->company_name }}</h6>
                            @if($job->company_website)
                                <a href="{{ $job->company_website }}" target="_blank" rel="noopener noreferrer"
                                    class="small text-decoration-none">
                                    <i class="bi bi-link-45deg me-1"></i>Website
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-semibold mb-0">
                        <i class="bi bi-person me-2"></i>Posted by
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <a href="{{ route('profile.show', $job->user->profile->username) }}">
                            <img src="{{ $job->user->profile->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($job->user->name) . '&size=48' }}"
                                alt="{{ $job->user->name }}" class="rounded-circle border"
                                style="width: 48px; height: 48px; object-fit: cover;">
                        </a>
                        <div>
                            <a href="{{ route('profile.show', $job->user->profile->username) }}"
                                class="text-decoration-none text-dark fw-semibold">
                                {{ $job->user->profile->username ?? $job->user->name }}
                            </a>
                            <small class="text-muted d-block">
                                <i class="bi bi-clock me-1"></i>Posted {{ $job->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-semibold mb-0">
                        <i class="bi bi-info-circle me-2 text-info"></i>Job Overview
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <small class="text-muted">Type</small>
                        <span class="fw-semibold small">{{ $job->type }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <small class="text-muted">Location</small>
                        <span class="fw-semibold small">{{ $job->location_type }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <small class="text-muted">Experience</small>
                        <span class="fw-semibold small">{{ $job->experience_level }}</span>
                    </div>
                    @if($job->salary_min || $job->salary_max)
                        <div class="d-flex justify-content-between mb-2">
                            <small class="text-muted">Salary</small>
                            <span class="fw-semibold small">
                                {{ $job->salary_currency ?? 'NPR' }}
                                @if($job->salary_min){{ number_format($job->salary_min) }}@endif
                                @if($job->salary_min && $job->salary_max) - @endif
                                @if($job->salary_max){{ number_format($job->salary_max) }}@endif
                            </span>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">Posted</small>
                        <span class="small">{{ $job->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('submit', async function(e) {
    const form = e.target.closest('.job-save-form');
    if (!form) return;
    e.preventDefault();

    const button = form.querySelector('button');
    const icon = button.querySelector('i');
    const text = button.querySelector('span');

    try {
        const response = await window.DevDoko.fetch(form.action, {
            method: form.querySelector('[name="_method"]') ? 'DELETE' : 'POST',
        });

        icon.classList.toggle('bi-bookmark-fill', response.saved);
        icon.classList.toggle('bi-bookmark', !response.saved);
        text.textContent = response.saved ? 'Saved' : 'Save Job';

        const methodInput = form.querySelector('[name="_method"]');
        if (response.saved && !methodInput) {
            form.insertAdjacentHTML('beforeend', '<input type="hidden" name="_method" value="DELETE">');
        } else if (!response.saved && methodInput) {
            methodInput.remove();
        }
    } catch (error) {
        window.DevDoko.toast('Something went wrong. Please try again.', 'error');
    }
});
</script>
@endsection
