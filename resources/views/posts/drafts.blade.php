{{-- resources/views/posts/drafts.blade.php --}}
@extends('layouts.app')

@section('title', 'My Drafts - DevDoko')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-semibold mb-0">
            <i class="bi bi-file-earmark-lock me-2 text-primary"></i>
            My Private Posts
        </h1>
        <a href="{{ route('posts.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-plus-lg me-2"></i> Create Post
        </a>
    </div>

    @if($posts->count() > 0)
    <div class="row g-4">
        @foreach($posts as $post)
        <div class="col-12">
            @include('posts.partials.card', ['post' => $post, 'fullView' => false])
        </div>
        @endforeach
    </div>
    <div class="mt-4">
        {{ $posts->links() }}
    </div>
    @else
    <div class="text-center py-5">
        <div class="app-bg-secondary rounded-circle d-inline-flex p-5 mb-4">
            <i class="bi bi-file-earmark-lock text-primary" style="font-size: 48px;"></i>
        </div>
        <h5 class="fw-semibold mb-2">No private posts yet</h5>
        <p class="app-text-muted mb-4">Posts you mark as private will show up here.</p>
        <a href="{{ route('posts.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i> Create Post
        </a>
    </div>
    @endif
</div>
@endsection
