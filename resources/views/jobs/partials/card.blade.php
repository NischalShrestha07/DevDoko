<div class="card border-0 shadow-sm h-100">
    <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <span class="badge bg-primary rounded-pill">{{ $job->type }}</span>
            @if($job->is_featured)
                <span class="badge bg-warning text-dark rounded-pill"><i class="bi bi-star-fill me-1"></i>Featured</span>
            @endif
        </div>

        <h5 class="fw-bold mb-1">
            <a href="{{ route('jobs.show', $job) }}" class="text-decoration-none app-text-primary stretched-link">
                {{ $job->title }}
            </a>
        </h5>
        <p class="app-text-muted mb-3">{{ $job->company_name }}</p>

        <div class="d-flex flex-wrap gap-3 mb-3 small app-text-muted">
            @if($job->location)
                <span><i class="bi bi-geo-alt me-1"></i>{{ $job->location }}</span>
            @endif
            <span><i class="bi bi-briefcase me-1"></i>{{ $job->location_type }}</span>
            @if($job->salary_min || $job->salary_max)
                <span>
                    <i class="bi bi-currency-exchange me-1"></i>
                    {{ $job->salary_currency ?? 'NPR' }}
                    @if($job->salary_min){{ number_format($job->salary_min) }}@endif
                    @if($job->salary_min && $job->salary_max) - @endif
                    @if($job->salary_max){{ number_format($job->salary_max) }}@endif
                </span>
            @endif
        </div>

        <div class="mb-3">
            <span class="badge bg-light text-dark border me-1">{{ $job->experience_level }}</span>
        </div>

        @if(is_array($job->required_skills) && count($job->required_skills) > 0)
            <div class="d-flex flex-wrap gap-1">
                @foreach(array_slice($job->required_skills, 0, 5) as $skill)
                    <span class="badge bg-light text-dark border">{{ $skill }}</span>
                @endforeach
                @if(count($job->required_skills) > 5)
                    <span class="badge bg-light text-muted border">+{{ count($job->required_skills) - 5 }} more</span>
                @endif
            </div>
        @endif
    </div>

    <div class="card-footer bg-white border-0 px-4 py-3">
        <div class="d-flex justify-content-between align-items-center">
            <small class="app-text-muted">
                <i class="bi bi-clock me-1"></i>{{ $job->created_at->diffForHumans() }}
            </small>
            <small class="app-text-muted">
                <i class="bi bi-eye me-1"></i>{{ $job->views_count }}
            </small>
        </div>
    </div>
</div>
