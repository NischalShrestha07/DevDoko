@extends('layouts.app')

@section('title', 'Home - DevDoko')

@section('content')
<!-- Stories -->
<div class="stories-container">
    @php
    // Sample stories - In real app, fetch from database
    $stories = [
    ['username' => 'laravel_master', 'avatar' => 'https://ui-avatars.com/api/?name=Laravel&background=random'],
    ['username' => 'react_dev', 'avatar' => 'https://ui-avatars.com/api/?name=React&background=random'],
    ['username' => 'python_pro', 'avatar' => 'https://ui-avatars.com/api/?name=Python&background=random'],
    ['username' => 'js_wizard', 'avatar' => 'https://ui-avatars.com/api/?name=JavaScript&background=random'],
    ['username' => 'aws_guru', 'avatar' => 'https://ui-avatars.com/api/?name=AWS&background=random'],
    ['username' => 'docker_expert', 'avatar' => 'https://ui-avatars.com/api/?name=Docker&background=random'],
    ['username' => 'vue_ninja', 'avatar' => 'https://ui-avatars.com/api/?name=Vue&background=random'],
    ['username' => 'node_hero', 'avatar' => 'https://ui-avatars.com/api/?name=Node&background=random'],
    ];
    @endphp

    @foreach($stories as $story)
    <div class="story-item">
        <div class="story-avatar">
            <img src="{{ $story['avatar'] }}" alt="{{ $story['username'] }}">
        </div>
        <div class="story-username">{{ $story['username'] }}</div>
    </div>
    @endforeach
</div>

<!-- Create Post Card -->
<div class="create-post-card">
    <div class="create-post-header">
        <img src="{{ auth()->user()->profile->avatar_url }}" alt="{{ auth()->user()->name }}" class="rounded-circle"
            style="width: 40px; height: 40px; object-fit: cover;">
        <div class="create-post-input" onclick="location.href='{{ route('posts.create') }}'">
            What's on your mind, {{ auth()->user()->name }}?
        </div>
    </div>
    <div class="create-post-options">
        <a href="{{ route('posts.create') }}?type=image" class="create-post-option photo">
            <i class="bi bi-image"></i>
            <span>Photo</span>
        </a>
        <a href="{{ route('posts.create') }}?type=video" class="create-post-option video">
            <i class="bi bi-play-btn"></i>
            <span>Video</span>
        </a>
        <a href="{{ route('posts.create') }}?type=code" class="create-post-option code">
            <i class="bi bi-code-slash"></i>
            <span>Code</span>
        </a>
        <a href="{{ route('posts.create') }}" class="create-post-option article">
            <i class="bi bi-file-text"></i>
            <span>Article</span>
        </a>
    </div>
</div>

<!-- Posts Feed -->
@forelse($posts as $post)
@include('posts.partials.card', ['post' => $post])
@empty
<!-- No Posts State -->
<div class="post-card">
    <div class="post-header">
        <div class="post-user">
            <img src="{{ auth()->user()->profile->avatar_url }}" alt="{{ auth()->user()->name }}" class="post-avatar">
            <a href="{{ route('profile.show', auth()->user()->profile->username) }}" class="post-username">
                {{ auth()->user()->profile->username }}
            </a>
        </div>
    </div>
    <div style="padding: 60px 20px; text-align: center;">
        <i class="bi bi-newspaper" style="font-size: 48px; color: #dbdbdb; margin-bottom: 16px;"></i>
        <h4 style="color: #8e8e8e; margin-bottom: 8px;">Your feed is empty</h4>
        <p style="color: #8e8e8e; margin-bottom: 24px;">
            Follow other developers or create your first post to get started!
        </p>
        <div style="display: flex; gap: 12px; justify-content: center;">
            <a href="{{ route('explore') }}"
                style="background: var(--primary-color); color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none;">
                <i class="bi bi-compass"></i> Explore Developers
            </a>
            <a href="{{ route('posts.create') }}"
                style="border: 1px solid var(--border-color); color: var(--text-color); padding: 8px 16px; border-radius: 8px; text-decoration: none;">
                <i class="bi bi-plus-circle"></i> Create First Post
            </a>
        </div>
    </div>
</div>
@endforelse

<!-- Pagination -->
@if($posts->hasPages())
<div style="text-align: center; margin: 24px 0;">
    {{ $posts->links() }}
</div>
@endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
            // Like functionality
            document.querySelectorAll('.like-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const postId = this.dataset.postId;
                    const icon = this.querySelector('i');

                    // Toggle like state
                    if (icon.classList.contains('bi-heart')) {
                        icon.classList.remove('bi-heart');
                        icon.classList.add('bi-heart-fill', 'liked');
                    } else {
                        icon.classList.remove('bi-heart-fill', 'liked');
                        icon.classList.add('bi-heart');
                    }

                    // Update likes count
                    const likesCount = document.querySelector(`.likes-count-${postId}`);
                    const currentCount = parseInt(likesCount.textContent);

                    if (icon.classList.contains('bi-heart-fill')) {
                        likesCount.textContent = `${currentCount + 1} likes`;
                    } else {
                        likesCount.textContent = `${currentCount - 1} likes`;
                    }
                });
            });

            // Comment input validation
            document.querySelectorAll('.comment-input').forEach(input => {
                const submitBtn = input.parentElement.querySelector('.comment-submit');

                input.addEventListener('input', function() {
                    if (this.value.trim().length > 0) {
                        submitBtn.classList.add('active');
                    } else {
                        submitBtn.classList.remove('active');
                    }
                });
            });

            // Save post
            document.querySelectorAll('.save-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const icon = this.querySelector('i');

                    if (icon.classList.contains('bi-bookmark')) {
                        icon.classList.remove('bi-bookmark');
                        icon.classList.add('bi-bookmark-fill');
                    } else {
                        icon.classList.remove('bi-bookmark-fill');
                        icon.classList.add('bi-bookmark');
                    }
                });
            });
        });
</script>
@endpush
@endsection