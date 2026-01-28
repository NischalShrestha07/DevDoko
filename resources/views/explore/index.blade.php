{{-- resources/views/explore/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Explore - DevDoko')

@section('content')
<div class="container">
    <!-- Trending Posts -->
    <div class="mb-5">
        <h5 class="fw-bold mb-3">Trending Now</h5>
        <div class="row">
            @foreach($trendingPosts as $post)
            <div class="col-md-4 col-sm-6 mb-4">
                <a href="{{ route('posts.show', $post) }}" class="text-decoration-none">
                    @if($post->type === 'image' && $post->media->count())
                    <img src="{{ asset('storage/' . $post->media->first()->file_path) }}" class="img-fluid rounded"
                        style="width: 100%; height: 300px; object-fit: cover;">
                    @elseif($post->type === 'code')
                    <div class="bg-dark text-light p-4 rounded h-100">
                        <div class="text-center">
                            <i class="bi bi-code-slash fs-1"></i>
                            <div class="mt-2">Code Snippet</div>
                        </div>
                    </div>
                    @else
                    <div class="bg-light p-4 rounded h-100">
                        <p class="mb-0">{{ Str::limit($post->caption, 100) }}</p>
                    </div>
                    @endif
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="bi bi-heart"></i> {{ $post->likes_count }}
                            <i class="bi bi-chat ms-2"></i> {{ $post->comments_count }}
                        </small>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Popular Developers -->
    <div class="mb-5">
        <h5 class="fw-bold mb-3">Popular Developers</h5>
        <div class="row">
            @foreach($popularDevelopers as $user)
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-0">
                    <div class="card-body text-center">
                        <a href="{{ route('profile.show', $user->profile->username) }}" class="text-decoration-none">
                            @if($user->profile->avatar)
                            <img src="{{ asset('storage/' . $user->profile->avatar) }}" class="rounded-circle mb-2"
                                style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                            <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center mb-2"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-person-fill text-white fs-3"></i>
                            </div>
                            @endif
                            <h6 class="fw-bold mb-1">{{ $user->profile->username }}</h6>
                            <small class="text-muted">{{ $user->name }}</small>
                            <div class="mt-2">
                                <small class="text-muted">
                                    {{ $user->followers_count }} followers
                                </small>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Popular Tags -->
    <div class="mb-5">
        <h5 class="fw-bold mb-3">Popular Tags</h5>
        <div class="d-flex flex-wrap gap-2">
            @foreach($popularTags as $tag)
            <a href="{{ route('tags.show', $tag->name) }}" class="btn btn-outline-primary btn-sm">
                #{{ $tag->name }}
                <span class="badge bg-light text-dark ms-1">{{ $tag->posts_count }}</span>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection