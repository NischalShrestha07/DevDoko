{{-- resources/views/welcome.blade.php --}}
@extends('layouts.guest')

@section('title', 'DevDoko - Developer Social Network')

@section('content')
<div class="container py-5">
    <div class="row align-items-center">
        <div class="col-lg-6 mb-5 mb-lg-0">
            <h1 class="display-4 fw-bold text-white mb-4">
                <i class="bi bi-code-slash"></i> DevDoko
            </h1>
            <p class="lead text-white mb-4">
                Connect, share, and grow with developers worldwide.
                Share your code, projects, and ideas with the developer community.
            </p>
            <div class="d-flex flex-wrap gap-3">
                <a href="{{ route('register') }}" class="btn btn-light btn-lg px-4">
                    <i class="bi bi-person-plus"></i> Join Now
                </a>
                <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4">
                    <i class="bi bi-box-arrow-in-right"></i> Sign In
                </a>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">What developers are sharing</h4>

                    <div class="row g-3">
                        @foreach($posts as $post)
                        <div class="col-md-6 col-12">
                            <div class="card border-0 shadow-sm h-100">
                                @if($post->type === 'image' && $post->media->count())
                                <img src="{{ asset('storage/' . $post->media->first()->file_path) }}"
                                    class="card-img-top" alt="Post image" style="height: 150px; object-fit: cover;">
                                @elseif($post->type === 'code')
                                <div class="bg-dark text-light p-3" style="height: 150px;">
                                    <div class="d-flex align-items-center h-100">
                                        <i class="bi bi-code-slash fs-1"></i>
                                        <div class="ms-3">
                                            <h6 class="mb-0">Code Snippet</h6>
                                            <small class="text-muted">{{ $post->codeSnippet->language ?? 'Code'
                                                }}</small>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="card-body">
                                    <p class="card-text text-truncate">{{ $post->caption }}</p>
                                </div>
                                @endif
                                <div class="card-footer bg-white border-0">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $post->user->profile->avatar_url }}" class="rounded-circle me-2"
                                            width="30" height="30" style="object-fit: cover;">
                                        <small class="text-muted">{{ $post->user->profile->username }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('explore') }}" class="text-decoration-none">
                            Explore more posts <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-12">
            <h3 class="text-white text-center mb-4">Why Join DevDoko?</h3>
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <div class="bg-white rounded p-4">
                        <i class="bi bi-code-square display-4 text-primary mb-3"></i>
                        <h5>Share Code</h5>
                        <p class="text-muted">Share your code snippets with syntax highlighting</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="bg-white rounded p-4">
                        <i class="bi bi-people display-4 text-primary mb-3"></i>
                        <h5>Connect</h5>
                        <p class="text-muted">Connect with developers worldwide</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="bg-white rounded p-4">
                        <i class="bi bi-briefcase display-4 text-primary mb-3"></i>
                        <h5>Get Hired</h5>
                        <p class="text-muted">Showcase your projects to potential employers</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection