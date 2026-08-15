@extends('layouts.app')

@section('title', 'Jobs - DevDoko')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-semibold mb-1">
                <i class="bi bi-briefcase-fill me-2 text-primary"></i>
                Jobs
            </h1>
            <p class="text-muted mb-0">Find your next opportunity</p>
        </div>
        @auth
            <a href="{{ route('jobs.create') }}" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-plus-lg me-2"></i>Post a Job
            </a>
        @endauth
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <form action="{{ route('jobs.index') }}" method="GET" class="mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3">
                        <div class="row g-2">
                            <div class="col-12 col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="bi bi-search text-muted"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control border-start-0 ps-0"
                                        placeholder="Search jobs..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <select name="type" class="form-select">
                                    <option value="">All Types</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <select name="location_type" class="form-select">
                                    <option value="">All Locations</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location }}" {{ request('location_type') === $location ? 'selected' : '' }}>{{ $location }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-md-2">
                                <select name="experience_level" class="form-select">
                                    <option value="">All Levels</option>
                                    @foreach($levels as $level)
                                        <option value="{{ $level }}" {{ request('experience_level') === $level ? 'selected' : '' }}>{{ $level }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-md-2 d-grid">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            @if(request()->anyFilled(['search', 'type', 'location_type', 'experience_level']))
                <div class="d-flex align-items-center gap-2 mb-3">
                    <small class="text-muted">Active filters:</small>
                    @if(request('search'))
                        <span class="badge app-bg-secondary app-text-primary border d-flex align-items-center gap-1">
                            Keyword: "{{ request('search') }}"
                            <a href="{{ route('jobs.index', array_merge(request()->except('search', 'page'), ['search' => ''])) }}" class="text-decoration-none app-text-muted ms-1">&times;</a>
                        </span>
                    @endif
                    @if(request('type'))
                        <span class="badge app-bg-secondary app-text-primary border d-flex align-items-center gap-1">
                            {{ request('type') }}
                            <a href="{{ route('jobs.index', array_merge(request()->except('type', 'page'), ['type' => ''])) }}" class="text-decoration-none app-text-muted ms-1">&times;</a>
                        </span>
                    @endif
                    @if(request('location_type'))
                        <span class="badge app-bg-secondary app-text-primary border d-flex align-items-center gap-1">
                            {{ request('location_type') }}
                            <a href="{{ route('jobs.index', array_merge(request()->except('location_type', 'page'), ['location_type' => ''])) }}" class="text-decoration-none app-text-muted ms-1">&times;</a>
                        </span>
                    @endif
                    @if(request('experience_level'))
                        <span class="badge app-bg-secondary app-text-primary border d-flex align-items-center gap-1">
                            {{ request('experience_level') }}
                            <a href="{{ route('jobs.index', array_merge(request()->except('experience_level', 'page'), ['experience_level' => ''])) }}" class="text-decoration-none app-text-muted ms-1">&times;</a>
                        </span>
                    @endif
                    <a href="{{ route('jobs.index') }}" class="small text-decoration-none ms-2">Clear all</a>
                </div>
            @endif

            @forelse($jobs as $job)
                <div class="mb-3 position-relative">
                    @include('jobs.partials.card', ['job' => $job])
                </div>
            @empty
                <div class="text-center py-5">
                    <div class="app-bg-secondary rounded-circle d-inline-flex p-5 mb-4">
                        <i class="bi bi-briefcase text-primary" style="font-size: 48px;"></i>
                    </div>
                    <h5 class="fw-semibold mb-2">No jobs found</h5>
                    <p class="app-text-muted mb-4">
                        @if(request()->anyFilled(['search', 'type', 'location_type', 'experience_level']))
                            Try adjusting your search filters
                        @else
                            No job listings available right now
                        @endif
                    </p>
                    @if(!request()->anyFilled(['search', 'type', 'location_type', 'experience_level']))
                        <a href="{{ route('jobs.create') }}" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-plus-lg me-2"></i>Post the First Job
                        </a>
                    @else
                        <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="bi bi-x-lg me-2"></i>Clear Filters
                        </a>
                    @endif
                </div>
            @endforelse

            <div class="mt-4">
                {{ $jobs->links('pagination::bootstrap-5') }}
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-semibold mb-0">
                        <i class="bi bi-funnel text-primary me-2"></i>
                        Quick Filters
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-uppercase fw-semibold text-secondary d-block mb-2">Job Type</small>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($types as $type)
                                <a href="{{ route('jobs.index', array_merge(request()->except('type', 'page'), ['type' => $type])) }}"
                                    class="badge {{ request('type') === $type ? 'bg-primary text-white' : 'app-bg-secondary app-text-primary border' }} text-decoration-none">
                                    {{ $type }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="mb-3">
                        <small class="text-uppercase fw-semibold text-secondary d-block mb-2">Experience Level</small>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($levels as $level)
                                <a href="{{ route('jobs.index', array_merge(request()->except('experience_level', 'page'), ['experience_level' => $level])) }}"
                                    class="badge {{ request('experience_level') === $level ? 'bg-primary text-white' : 'app-bg-secondary app-text-primary border' }} text-decoration-none">
                                    {{ $level }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <small class="text-uppercase fw-semibold text-secondary d-block mb-2">Location Type</small>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($locations as $location)
                                <a href="{{ route('jobs.index', array_merge(request()->except('location_type', 'page'), ['location_type' => $location])) }}"
                                    class="badge {{ request('location_type') === $location ? 'bg-primary text-white' : 'app-bg-secondary app-text-primary border' }} text-decoration-none">
                                    {{ $location }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="fw-semibold mb-0">
                        <i class="bi bi-info-circle text-info me-2"></i>
                        Tips
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="small text-muted mb-0 ps-3">
                        <li class="mb-1">Be specific about required skills</li>
                        <li class="mb-1">Mention salary range for more applications</li>
                        <li class="mb-1">Clear job descriptions attract better talent</li>
                        <li>Include company culture details</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
