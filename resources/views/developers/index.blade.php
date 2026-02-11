{{-- resources/views/developers/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Find Developers - DevDoko')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-semibold">
            <i class="bi bi-people-fill me-2 text-primary"></i>
            Find Developers
        </h1>
        <form action="{{ route('developers.index') }}" method="GET" class="d-flex gap-2">
            <input type="search" name="search" class="form-control" placeholder="Search developers by name, skills..."
                value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-search"></i>
            </button>
        </form>
    </div>

    <!-- Skill Filter Pills -->
    <div class="mb-4">
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('developers.index') }}"
                class="btn btn-sm {{ !request('skill') ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill px-4">
                All
            </a>
            @php
            $popularSkills = ['PHP', 'JavaScript', 'Python', 'Laravel', 'React', 'Vue', 'Node.js', 'Java', 'C#'];
            @endphp
            @foreach($popularSkills as $skill)
            <a href="{{ route('developers.index', ['skill' => $skill, 'search' => request('search')]) }}"
                class="btn btn-sm {{ request('skill') === $skill ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill">
                {{ $skill }}
            </a>
            @endforeach
        </div>
    </div>

    <!-- Sort Options -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <p class="text-muted mb-0">
            Showing {{ $developers->firstItem() }}-{{ $developers->lastItem() }} of {{ $developers->total() }}
            developers
        </p>
        <div class="dropdown">
            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-sort me-1"></i>
                Sort by: {{ ucfirst(request('sort', 'recent')) }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item {{ request('sort', 'recent') === 'recent' ? 'active' : '' }}"
                        href="{{ route('developers.index', array_merge(request()->query(), ['sort' => 'recent'])) }}">Recent</a>
                </li>
                <li><a class="dropdown-item {{ request('sort') === 'popular' ? 'active' : '' }}"
                        href="{{ route('developers.index', array_merge(request()->query(), ['sort' => 'popular'])) }}">Most
                        Followers</a></li>
                <li><a class="dropdown-item {{ request('sort') === 'active' ? 'active' : '' }}"
                        href="{{ route('developers.index', array_merge(request()->query(), ['sort' => 'active'])) }}">Most
                        Active</a></li>
            </ul>
        </div>
    </div>

    <div class="row g-4">
        @forelse($developers as $developer)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm hover-lift">
                <div class="card-body p-4">
                    <!-- Developer Header -->
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <a href="{{ route('profile.show', $developer->profile->username) }}">
                            <img src="{{ $developer->avatar_url }}" alt="{{ $developer->name }}"
                                class="rounded-circle border" style="width: 72px; height: 72px; object-fit: cover;">
                        </a>
                        <div class="flex-grow-1">
                            <a href="{{ route('profile.show', $developer->profile->username) }}"
                                class="text-decoration-none text-dark">
                                <h5 class="fw-semibold mb-1">{{ $developer->profile->username ?? $developer->name }}
                                </h5>
                            </a>
                            <p class="text-muted small mb-1">{{ $developer->profile->title ?? 'Developer' }}</p>
                            @if($developer->isOnline())
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill">
                                <span class="dot me-1 bg-success"
                                    style="width: 8px; height: 8px; display: inline-block; border-radius: 50%;"></span>
                                Online
                            </span>
                            @else
                            <span class="badge bg-light text-muted rounded-pill small">
                                Last seen {{ $developer->last_login_at?->diffForHumans() ?? 'recently' }}
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Skills/Tags -->
                    @if($developer->profile->skills)
                    <div class="mb-3">
                        @php
                        $skills = is_array($developer->profile->skills)
                        ? $developer->profile->skills
                        : explode(',', $developer->profile->skills);
                        @endphp
                        @foreach(array_slice($skills, 0, 3) as $skill)
                        <span class="badge bg-light text-dark me-1 mb-1 px-3 py-2 rounded-pill">
                            {{ trim($skill) }}
                        </span>
                        @endforeach
                        @if(count($skills) > 3)
                        <span class="badge bg-light text-dark px-3 py-2 rounded-pill">
                            +{{ count($skills) - 3 }}
                        </span>
                        @endif
                    </div>
                    @endif

                    <!-- Bio -->
                    <p class="small text-muted mb-3" style="min-height: 40px;">
                        {{ Str::limit($developer->profile->bio ?? 'No bio yet', 80) }}
                    </p>

                    <!-- Stats -->
                    <div class="d-flex justify-content-around mb-3 pt-2 border-top">
                        <div class="text-center">
                            <span class="fw-bold">{{ $developer->followers_count ?? 0 }}</span>
                            <small class="text-muted d-block">Followers</small>
                        </div>
                        <div class="text-center">
                            <span class="fw-bold">{{ $developer->posts_count ?? 0 }}</span>
                            <small class="text-muted d-block">Posts</small>
                        </div>
                        <div class="text-center">
                            <span class="fw-bold">{{ $developer->projects_count ?? 0 }}</span>
                            <small class="text-muted d-block">Projects</small>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <a href="{{ route('profile.show', $developer->profile->username) }}"
                            class="btn btn-outline-primary flex-grow-1">
                            <i class="bi bi-person me-1"></i> Profile
                        </a>

                        @auth
                        @if(auth()->id() !== $developer->id)
                        <!-- Message Button -->
                        <a href="{{ route('messages.show', $developer) }}" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-chat me-1"></i> Message
                        </a>

                        <!-- Follow/Unfollow Button -->
                        <form action="{{ route('users.follow', $developer) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit"
                                class="btn {{ auth()->user()->isFollowing($developer) ? 'btn-outline-secondary' : 'btn-outline-primary' }}"
                                style="width: 45px;">
                                <i
                                    class="bi bi-{{ auth()->user()->isFollowing($developer) ? 'person-check' : 'person-plus' }}"></i>
                            </button>
                        </form>
                        @else
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary flex-grow-1">
                            <i class="bi bi-pencil me-1"></i> Edit Profile
                        </a>
                        @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <div class="bg-light rounded-circle d-inline-flex p-5 mb-4">
                    <i class="bi bi-people fs-1 text-muted"></i>
                </div>
                <h5 class="mt-3">No developers found</h5>
                <p class="text-muted mb-3">Try adjusting your search or filters</p>
                <a href="{{ route('developers.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-repeat me-2"></i>Clear Filters
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <div class="mt-5">
        {{ $developers->withQueryString()->links() }}
    </div>
</div>

<style>
    .hover-lift {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
    }

    .dot {
        display: inline-block;
        border-radius: 50%;
        margin-right: 4px;
    }
</style>

@endsection