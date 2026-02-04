@extends('layouts.app')

@section('title', 'Projects - DevDoko')

@section('content')
<div class="container-fluid">
    <!-- Hero Section -->
    <div class="row mb-5 fade-in">
        <div class="col-md-8 mx-auto text-center">
            <h1 class="display-5 fw-bold mb-3">Explore Developer Projects</h1>
            <p class="lead text-muted mb-4">
                Discover amazing projects, contribute to open source, or showcase your own work.
                Connect with developers and collaborate on innovative ideas.
            </p>
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <a href="{{ route('projects.create') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-plus-circle me-2"></i> Add Project
                </a>
                <a href="{{ route('collaboration.index') }}" class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-people me-2"></i> Find Collaborators
                </a>
            </div>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="card border-0 shadow-sm mb-5 fade-in">
        <div class="card-body">
            <div class="row g-4">
                <!-- Search -->
                <div class="col-md-6">
                    <form action="{{ route('projects.index') }}" method="GET">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" name="search" class="form-control border-start-0"
                                placeholder="Search projects..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
                    </form>
                </div>

                <!-- Sort -->
                <div class="col-md-3">
                    <select class="form-select" name="sort" onchange="this.form.submit()" form="filter-form">
                        <option value="recent" {{ request('sort', 'recent' )=='recent' ? 'selected' : '' }}>
                            Most Recent
                        </option>
                        <option value="popular" {{ request('sort')=='popular' ? 'selected' : '' }}>
                            Most Popular
                        </option>
                        <option value="trending" {{ request('sort')=='trending' ? 'selected' : '' }}>
                            Trending
                        </option>
                    </select>
                </div>

                <!-- Filter Button -->
                <div class="col-md-3">
                    <button class="btn btn-outline-secondary w-100" type="button" data-bs-toggle="collapse"
                        data-bs-target="#filterCollapse">
                        <i class="bi bi-funnel me-2"></i> Filters
                    </button>
                </div>
            </div>

            <!-- Advanced Filters -->
            <div class="collapse mt-4" id="filterCollapse">
                <form id="filter-form" action="{{ route('projects.index') }}" method="GET">
                    <div class="row g-3">
                        <!-- Category -->
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Category</label>
                            <select class="form-select" name="category" onchange="this.form.submit()">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category')==$category ? 'selected' : '' }}>
                                    {{ ucfirst($category) }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Technology -->
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Technology</label>
                            <select class="form-select" name="technology" onchange="this.form.submit()">
                                <option value="">All Technologies</option>
                                @foreach($technologies as $tech)
                                <option value="{{ $tech }}" {{ request('technology')==$tech ? 'selected' : '' }}>
                                    {{ $tech }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Difficulty -->
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Difficulty</label>
                            <select class="form-select" name="difficulty" onchange="this.form.submit()">
                                <option value="">All Levels</option>
                                <option value="beginner" {{ request('difficulty')=='beginner' ? 'selected' : '' }}>
                                    Beginner
                                </option>
                                <option value="intermediate" {{ request('difficulty')=='intermediate' ? 'selected' : ''
                                    }}>
                                    Intermediate
                                </option>
                                <option value="advanced" {{ request('difficulty')=='advanced' ? 'selected' : '' }}>
                                    Advanced
                                </option>
                                <option value="expert" {{ request('difficulty')=='expert' ? 'selected' : '' }}>
                                    Expert
                                </option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Featured Projects -->
    @if($projects->where('is_featured', true)->count() > 0)
    <div class="mb-5 fade-in">
        <h3 class="fw-bold mb-4">
            <i class="bi bi-star-fill text-warning me-2"></i> Featured Projects
        </h3>
        <div class="row g-4">
            @foreach($projects->where('is_featured', true)->take(3) as $project)
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 project-card">
                    <div class="project-thumbnail" style="background: linear-gradient(45deg, #667eea, #764ba2);">
                        @if($project->thumbnail_url)
                        <img src="{{ $project->thumbnail_url }}" alt="{{ $project->title }}"
                            style="width: 100%; height: 200px; object-fit: cover;">
                        @else
                        <i class="bi bi-layers text-white" style="font-size: 48px;"></i>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title fw-bold mb-0">{{ $project->title }}</h5>
                            <span class="badge bg-warning">Featured</span>
                        </div>
                        <p class="card-text text-muted small mb-3">{{ $project->short_description }}</p>

                        <div class="mb-3">
                            @foreach($project->techBadges as $tech)
                            <span class="dev-badge primary small mb-1">
                                <i class="bi bi-tag-fill"></i> {{ $tech }}
                            </span>
                            @endforeach
                        </div>

                        <div class="d-flex align-items-center">
                            <img src="{{ $project->user->profile->avatar_url }}" alt="{{ $project->user->name }}"
                                class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">
                            <small class="text-muted">
                                <a href="{{ route('profile.show', $project->user->profile->username) }}"
                                    class="text-decoration-none text-dark fw-bold">
                                    {{ $project->user->name }}
                                </a>
                            </small>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="project-stats small">
                                <span class="text-muted me-3">
                                    <i class="bi bi-eye"></i> {{ $project->views_count }}
                                </span>
                                <span class="text-muted me-3">
                                    <i class="bi bi-heart"></i> {{ $project->likes_count }}
                                </span>
                                <span class="text-muted">
                                    <i class="bi bi-share"></i> {{ $project->forks_count }}
                                </span>
                            </div>
                            <a href="{{ route('projects.show', $project) }}" class="btn btn-primary btn-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- All Projects -->
    <div class="fade-in">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">All Projects</h3>
            <span class="text-muted">{{ $projects->total() }} projects</span>
        </div>

        @if($projects->count() > 0)
        <div class="row g-4">
            @foreach($projects as $project)
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100 project-card">
                    <div class="project-thumbnail position-relative">
                        @if($project->thumbnail_url)
                        <img src="{{ $project->thumbnail_url }}" alt="{{ $project->title }}"
                            style="width: 100%; height: 180px; object-fit: cover;">
                        @else
                        <div class="d-flex align-items-center justify-content-center h-100"
                            style="background: linear-gradient(45deg, #667eea, #764ba2);">
                            <i class="bi bi-layers text-white" style="font-size: 36px;"></i>
                        </div>
                        @endif
                        @if($project->forked_from_id)
                        <span class="position-absolute top-0 end-0 m-2 badge bg-secondary">
                            <i class="bi bi-diagram-2"></i> Forked
                        </span>
                        @endif
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-2">{{ $project->title }}</h5>
                        <p class="card-text text-muted small mb-3">{{ $project->short_description }}</p>

                        <div class="mb-3">
                            @foreach($project->techBadges as $tech)
                            <span class="dev-badge secondary small mb-1">
                                {{ $tech }}
                            </span>
                            @endforeach
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $project->user->profile->avatar_url }}" alt="{{ $project->user->name }}"
                                class="rounded-circle me-2" style="width: 30px; height: 30px; object-fit: cover;">
                            <small class="text-muted">
                                <a href="{{ route('profile.show', $project->user->profile->username) }}"
                                    class="text-decoration-none text-dark fw-bold">
                                    {{ $project->user->name }}
                                </a>
                            </small>
                            <span class="badge bg-light text-dark ms-auto">
                                <i class="bi bi-{{ $project->difficulty }}"></i>
                                {{ ucfirst($project->difficulty) }}
                            </span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="project-stats small">
                                <span class="text-muted me-3">
                                    <i class="bi bi-eye"></i> {{ $project->views_count }}
                                </span>
                                <span class="text-muted me-3">
                                    <i class="bi bi-heart"></i> {{ $project->likes_count }}
                                </span>
                                <span class="text-muted">
                                    <i class="bi bi-share"></i> {{ $project->forks_count }}
                                </span>
                            </div>
                            <a href="{{ route('projects.show', $project) }}" class="btn btn-outline-primary btn-sm">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-5">
            {{ $projects->links() }}
        </div>
        @else
        <!-- No Projects State -->
        <div class="text-center py-5">
            <i class="bi bi-layers display-1 text-muted mb-3"></i>
            <h4 class="text-muted">No projects found</h4>
            <p class="text-muted mb-4">
                @if(request()->hasAny(['search', 'category', 'technology', 'difficulty']))
                Try different filters or create the first project in this category!
                @else
                Be the first to showcase your project!
                @endif
            </p>
            <a href="{{ route('projects.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i> Add Your First Project
            </a>
        </div>
        @endif
    </div>

    <!-- Tech Stack Section -->
    <div class="mt-5 fade-in">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold mb-3">Popular Technologies</h5>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($technologies as $tech)
                    <a href="{{ route('projects.index') }}?technology={{ urlencode($tech) }}"
                        class="btn btn-outline-secondary btn-sm">
                        {{ $tech }}
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .project-card {
        transition: transform 0.3s, box-shadow 0.3s;
        border-radius: 12px;
        overflow: hidden;
    }

    .project-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
    }

    .project-thumbnail {
        height: 180px;
        overflow: hidden;
        background: #f8f9fa;
    }

    .project-stats i {
        font-size: 14px;
    }

    .dev-badge.primary {
        background: rgba(0, 149, 246, 0.1);
        color: var(--primary-color);
        border: 1px solid rgba(0, 149, 246, 0.2);
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 12px;
    }

    .dev-badge.secondary {
        background: rgba(108, 117, 125, 0.1);
        color: #6c757d;
        border: 1px solid rgba(108, 117, 125, 0.2);
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 12px;
    }
</style>
@endpush
@endsection