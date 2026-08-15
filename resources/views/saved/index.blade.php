@extends('layouts.app')

@section('title', 'Saved Posts - DevDoko')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="h3 fw-semibold mb-4">
                <i class="bi bi-bookmark-fill text-primary me-2"></i>
                Saved Posts
            </h1>

            @forelse($savedPosts as $post)
                @include('posts.partials.card', ['post' => $post, 'fullView' => false])
            @empty
                <div class="text-center py-5">
                    <div class="app-bg-secondary rounded-circle d-inline-flex p-5 mb-4">
                        <i class="bi bi-bookmark text-primary" style="font-size: 48px;"></i>
                    </div>
                    <h5 class="fw-semibold mb-2">No saved posts yet</h5>
                    <p class="app-text-muted mb-4">Posts you save will show up here</p>
                    <a href="{{ route('posts.index') }}" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-newspaper me-2"></i>Browse Posts
                    </a>
                </div>
            @endforelse

            <div class="mt-4">
                {{ $savedPosts->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection
