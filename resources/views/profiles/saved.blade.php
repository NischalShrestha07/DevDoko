@extends('layouts.app')

@section('title', ($user->name ?? 'User') . "'s Saved Posts - DevDoko")

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-semibold mb-0">
            <i class="bi bi-bookmark-fill me-2 text-primary"></i>
            Saved Posts
        </h1>
        <a href="{{ route('profile.show', $user->profile->username ?? $user->name) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Profile
        </a>
    </div>

    @if($savedPosts->count() > 0)
    <div class="row g-4">
        @foreach($savedPosts as $save)
        @if($save->post)
        <div class="col-12">
            @include('posts.partials.card', ['post' => $save->post, 'fullView' => false])
        </div>
        @endif
        @endforeach
    </div>
    <div class="mt-4">
        {{ $savedPosts->links() }}
    </div>
    @else
    <div class="text-center py-5">
        <div class="app-bg-secondary rounded-circle d-inline-flex p-5 mb-4">
            <i class="bi bi-bookmark text-primary" style="font-size: 48px;"></i>
        </div>
        <h5 class="fw-semibold mb-2">No saved posts yet</h5>
        <p class="app-text-muted mb-0">Posts saved by this user will show up here.</p>
    </div>
    @endif
</div>
@endsection
