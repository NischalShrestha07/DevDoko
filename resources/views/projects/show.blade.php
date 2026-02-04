@extends('layouts.app')

@section('title', $project->title . ' - DevDoko')

@section('content')
<div class="container-fluid">
    <!-- Project Header -->
    <div class="row mb-4 fade-in">
        <div class="col-lg-8">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('projects.index') }}">Projects</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $project->title }}</li>
                </ol>
            </nav>

            <!-- Project Title & Actions -->
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h1 class="display-6 fw-bold mb-2">{{ $project->title }}</h1>
                    <div class="d-flex align-items-center flex-wrap gap-2 mb-3">
                        <span class="badge bg-primary">{{ ucfirst($project->category) }}</span>
                        <span
                            class="badge bg-{{ $project->difficulty == 'beginner' ? 'success' : ($project->difficulty == 'intermediate' ? 'warning' : 'danger') }}">
                            {{ ucfirst($project->difficulty) }}
                        </span>
                        @if($project->forked_from_id)
                        <span class="badge bg-secondary">
                            <i class="bi bi-diagram-2 me-1"></i> Forked
                        </span>
                        @endif
                        @if($project->is_featured)
                        <span class="badge bg-warning">
                            <i class="bi bi-star-fill me-1"></i> Featured
                        </span>
                        @endif
                        @if(!$project->is_public)
                        <span class="badge bg-dark">
                            <i class="bi bi-lock me-1"></i> Private
                        </span>
                        @endif
                    </div>
                </div>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-three-dots"></i>
                    </button>
                    <ul class="dropdown-menu">
                        @if(auth()->id() == $project->user_id)
                        <li>
                            <a class="dropdown-item" href="{{ route('projects.edit', $project) }}">
                                <i class="bi bi-pencil me-2"></i> Edit Project
                            </a>
                        </li>
                        @endif
                        <li>
                            <button class="dropdown-item" onclick="shareProject()">
                                <i class="bi bi-share me-2"></i> Share
                            </button>
                        </li>
                        @if(auth()->id() != $project->user_id)
                        <li>
                            <form action="{{ route('projects.fork', $project) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-diagram-2 me-2"></i> Fork Project
                                </button>
                            </form>
                        </li>
                        <li>
                            <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#collaborateModal">
                                <i class="bi bi-people me-2"></i> Request Collaboration
                            </button>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Project Owner -->
            <div class="d-flex align-items-center mb-4 p-3 border rounded">
                <img src="{{ $project->user->profile->avatar_url }}" alt="{{ $project->user->name }}"
                    class="rounded-circle me-3" style="width: 56px; height: 56px; object-fit: cover;">
                <div style="flex: 1;">
                    <a href="{{ route('profile.show', $project->user->profile->username) }}"
                        class="fw-bold text-dark text-decoration-none d-block">
                        {{ $project->user->name }}
                    </a>
                    <small class="text-muted">Project Owner</small>
                </div>
                @if(auth()->id() != $project->user_id)
                <button class="btn btn-outline-primary btn-sm follow-btn" data-user-id="{{ $project->user->id }}">
                    {{ auth()->user()->isFollowing($project->user) ? 'Following' : 'Follow' }}
                </button>
                @endif
            </div>
        </div>

        <!-- Stats Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Project Stats</h6>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center p-3 border rounded">
                                <div class="display-6 fw-bold text-primary">{{ $project->views_count }}</div>
                                <small class="text-muted">Views</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 border rounded">
                                <div class="display-6 fw-bold text-success">{{ $project->likes_count }}</div>
                                <small class="text-muted">Likes</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 border rounded">
                                <div class="display-6 fw-bold text-warning">{{ $project->forks_count }}</div>
                                <small class="text-muted">Forks</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 border rounded">
                                <div class="display-6 fw-bold text-info">{{ $project->contributors->count() }}</div>
                                <small class="text-muted">Contributors</small>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-4">
                        <div class="d-grid gap-2">
                            <button class="btn btn-primary btn-lg like-btn" data-project-id="{{ $project->id }}"
                                data-liked="{{ $isLiked ? 'true' : 'false' }}">
                                <i class="bi bi-heart{{ $isLiked ? '-fill' : '' }} me-2"></i>
                                {{ $isLiked ? 'Liked' : 'Like' }}
                            </button>

                            @if($project->repository_url)
                            <a href="{{ $project->repository_url }}" target="_blank"
                                class="btn btn-outline-dark btn-lg">
                                <i class="bi bi-github me-2"></i> View Code
                            </a>
                            @endif

                            @if($project->live_url)
                            <a href="{{ $project->live_url }}" target="_blank" class="btn btn-outline-success btn-lg">
                                <i class="bi bi-box-arrow-up-right me-2"></i> Live Demo
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row fade-in">
        <!-- Left Column: Project Details -->
        <div class="col-lg-8">
            <!-- Screenshots Carousel -->
            @if($project->screenshots && count($project->screenshots) > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div id="screenshotsCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner rounded">
                        @foreach($project->screenshots as $index => $screenshot)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                            <img src="{{ Storage::url($screenshot) }}" class="d-block w-100"
                                alt="Screenshot {{ $index + 1 }}"
                                style="height: 400px; object-fit: contain; background: #f8f9fa;">
                        </div>
                        @endforeach
                    </div>
                    @if(count($project->screenshots) > 1)
                    <button class="carousel-control-prev" type="button" data-bs-target="#screenshotsCarousel"
                        data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#screenshotsCarousel"
                        data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                    @endif
                </div>
            </div>
            @endif

            <!-- Project Description -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">About this project</h5>
                    <div class="project-description">
                        {!! nl2br(e($project->description)) !!}
                    </div>
                </div>
            </div>

            <!-- Technologies Used -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Technologies Used</h5>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($project->technologies as $tech)
                        <span class="dev-badge primary">
                            <i class="bi bi-tag-fill"></i> {{ $tech }}
                        </span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Contributors -->
            @if($project->contributors->count() > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Contributors</h5>
                        <span class="badge bg-primary">{{ $project->contributors->count() }}</span>
                    </div>
                    <div class="row g-3">
                        @foreach($project->contributors as $contributor)
                        <div class="col-6 col-md-4">
                            <div class="d-flex align-items-center p-3 border rounded">
                                <img src="{{ $contributor->profile->avatar_url }}" alt="{{ $contributor->name }}"
                                    class="rounded-circle me-3" style="width: 45px; height: 45px; object-fit: cover;">
                                <div style="flex: 1; min-width: 0;">
                                    <a href="{{ route('profile.show', $contributor->profile->username) }}"
                                        class="fw-bold text-dark text-decoration-none d-block text-truncate">
                                        {{ $contributor->name }}
                                    </a>
                                    <small class="text-muted d-block text-truncate">
                                        {{ $contributor->pivot->role }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Forks -->
            @if($project->forks->count() > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Project Forks</h5>
                        <span class="badge bg-primary">{{ $project->forks_count }}</span>
                    </div>
                    <div class="row g-3">
                        @foreach($project->forks->take(6) as $fork)
                        <div class="col-6 col-md-4">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body">
                                    <h6 class="fw-bold">{{ $fork->title }}</h6>
                                    <small class="text-muted d-block mb-2">Forked by {{ $fork->user->name }}</small>
                                    <a href="{{ route('projects.show', $fork) }}"
                                        class="btn btn-sm btn-outline-primary w-100">
                                        View Fork
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column: Sidebar -->
        <div class="col-lg-4">
            <!-- Project Links -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Project Links</h6>
                    @if($project->repository_url)
                    <a href="{{ $project->repository_url }}" target="_blank"
                        class="d-flex align-items-center p-3 border rounded mb-3 text-decoration-none text-dark">
                        <i class="bi bi-github fs-4 me-3"></i>
                        <div>
                            <div class="fw-bold">Repository</div>
                            <small class="text-muted">View source code</small>
                        </div>
                    </a>
                    @endif

                    @if($project->live_url)
                    <a href="{{ $project->live_url }}" target="_blank"
                        class="d-flex align-items-center p-3 border rounded mb-3 text-decoration-none text-dark">
                        <i class="bi bi-box-arrow-up-right fs-4 me-3"></i>
                        <div>
                            <div class="fw-bold">Live Demo</div>
                            <small class="text-muted">Try it live</small>
                        </div>
                    </a>
                    @endif
                </div>
            </div>

            <!-- Related Projects -->
            @if($relatedProjects->count() > 0)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Related Projects</h6>
                    <div class="row g-3">
                        @foreach($relatedProjects as $related)
                        <div class="col-12">
                            <div class="d-flex align-items-center p-3 border rounded">
                                <div class="me-3">
                                    <div
                                        style="width: 50px; height: 50px; background: linear-gradient(45deg, #667eea, #764ba2);
                                                      border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-layers text-white"></i>
                                    </div>
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <a href="{{ route('projects.show', $related) }}"
                                        class="fw-bold text-dark text-decoration-none d-block text-truncate">
                                        {{ $related->title }}
                                    </a>
                                    <small class="text-muted d-block text-truncate">
                                        {{ $related->user->name }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Collaboration Section -->
            @if(auth()->id() != $project->user_id && $project->is_public)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Want to Collaborate?</h6>
                    <p class="text-muted small mb-3">
                        Interested in contributing to this project? Send a collaboration request to the owner.
                    </p>
                    <button class="btn btn-outline-primary w-100" data-bs-toggle="modal"
                        data-bs-target="#collaborateModal">
                        <i class="bi bi-people me-2"></i> Request Collaboration
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Collaborate Modal -->
<div class="modal fade" id="collaborateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Request Collaboration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('projects.request.collaboration', $project) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="text-muted mb-4">
                        Tell the project owner why you want to collaborate and what you can contribute.
                    </p>

                    <div class="mb-3">
                        <label class="form-label">Role you're interested in</label>
                        <input type="text" name="role" class="form-control"
                            placeholder="e.g., Frontend Developer, Backend Developer, Designer..." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Your message</label>
                        <textarea name="message" class="form-control" rows="4"
                            placeholder="Tell about your experience and why you want to collaborate..."
                            required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Like functionality
    document.querySelector('.like-btn').addEventListener('click', async function() {
        const button = this;
        const projectId = button.dataset.projectId;
        const isLiked = button.dataset.liked === 'true';

        try {
            const response = await fetch(`/projects/${projectId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                const data = await response.json();
                button.innerHTML = `
                    <i class="bi bi-heart${data.liked ? '-fill' : ''} me-2"></i>
                    ${data.liked ? 'Liked' : 'Like'}
                `;
                button.dataset.liked = data.liked;

                // Update stats card
                document.querySelector('.like-btn').closest('.row')
                    .querySelector('.text-success').previousElementSibling.textContent = data.likes_count;
            }
        } catch (error) {
            console.error('Error liking project:', error);
        }
    });

    // Follow functionality
    document.querySelector('.follow-btn')?.addEventListener('click', async function() {
        const button = this;
        const userId = button.dataset.userId;
        const isFollowing = button.textContent === 'Following';

        try {
            const response = await fetch(`/users/${userId}/follow`, {
                method: isFollowing ? 'DELETE' : 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });

            if (response.ok) {
                button.textContent = isFollowing ? 'Follow' : 'Following';
                button.classList.toggle('btn-outline-primary');
                button.classList.toggle('btn-primary');
            }
        } catch (error) {
            console.error('Error following user:', error);
        }
    });

    // Share project
    function shareProject() {
        if (navigator.share) {
            navigator.share({
                title: '{{ $project->title }}',
                text: 'Check out this project on DevDoko: {{ $project->short_description }}',
                url: window.location.href
            });
        } else {
            // Fallback: Copy to clipboard
            navigator.clipboard.writeText(window.location.href).then(() => {
                alert('Link copied to clipboard!');
            });
        }
    }

    // Initialize carousel
    const carousel = new bootstrap.Carousel(document.getElementById('screenshotsCarousel'));
</script>

<style>
    .project-description {
        line-height: 1.8;
        font-size: 1.05rem;
    }

    .project-description p {
        margin-bottom: 1.5rem;
    }

    .dev-badge.primary {
        background: rgba(0, 149, 246, 0.1);
        color: var(--primary-color);
        border: 1px solid rgba(0, 149, 246, 0.2);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        margin-bottom: 8px;
        margin-right: 8px;
    }

    .carousel-item {
        transition: transform 0.6s ease-in-out;
    }
</style>
@endpush
@endsection