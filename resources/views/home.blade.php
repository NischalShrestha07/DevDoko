@extends('layouts.app')

@section('title', 'Home - DevDoko')

@section('content')
<div class="container-fluid px-0">
    <!-- Stories Section -->
    <div class="bg-white border-bottom py-3" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
        <div class="d-flex px-3" style="gap: 16px;">
            <!-- My Story -->
            <div class="text-center" style="width: 80px; flex-shrink: 0;">
                <div class="position-relative mx-auto mb-2">
                    <div class="rounded-circle border border-3 border-primary"
                        style="width: 64px; height: 64px; padding: 3px;">
                        <img src="{{ auth()->user()->profile->avatar_url }}" alt="{{ auth()->user()->name }}"
                            class="rounded-circle w-100 h-100" style="object-fit: cover;">
                    </div>
                    <button
                        class="btn btn-primary btn-sm p-0 rounded-circle position-absolute bottom-0 end-0 border border-2 border-white"
                        style="width: 20px; height: 20px;">
                        <i class="bi bi-plus" style="font-size: 10px;"></i>
                    </button>
                </div>
                <small class="text-truncate d-block" style="font-size: 11px; max-width: 80px;">Your Story</small>
            </div>

            <!-- Followed Users Stories -->
            @php
            $stories = [
            ['username' => 'laravel_master', 'avatar' =>
            'https://ui-avatars.com/api/?name=Laravel&background=0066cc&color=fff', 'hasNew' => true],
            ['username' => 'react_dev', 'avatar' =>
            'https://ui-avatars.com/api/?name=React&background=61dafb&color=fff', 'hasNew' => true],
            ['username' => 'python_pro', 'avatar' =>
            'https://ui-avatars.com/api/?name=Python&background=3776ab&color=fff', 'hasNew' => false],
            ['username' => 'js_wizard', 'avatar' =>
            'https://ui-avatars.com/api/?name=JavaScript&background=f7df1e&color=000', 'hasNew' => true],
            ['username' => 'aws_guru', 'avatar' => 'https://ui-avatars.com/api/?name=AWS&background=ff9900&color=fff',
            'hasNew' => false],
            ['username' => 'docker_expert', 'avatar' =>
            'https://ui-avatars.com/api/?name=Docker&background=2496ed&color=fff', 'hasNew' => true],
            ['username' => 'vue_ninja', 'avatar' => 'https://ui-avatars.com/api/?name=Vue&background=42b883&color=fff',
            'hasNew' => false],
            ['username' => 'node_hero', 'avatar' => 'https://ui-avatars.com/api/?name=Node&background=339933&color=fff',
            'hasNew' => true],
            ];
            @endphp

            @foreach($stories as $story)
            <div class="text-center" style="width: 80px; flex-shrink: 0;">
                <div class="position-relative mx-auto mb-2">
                    <div class="rounded-circle border border-3 {{ $story['hasNew'] ? 'border-primary' : 'border-secondary' }}"
                        style="width: 64px; height: 64px; padding: 3px; cursor: pointer;">
                        <img src="{{ $story['avatar'] }}" alt="{{ $story['username'] }}"
                            class="rounded-circle w-100 h-100" style="object-fit: cover;">
                    </div>
                </div>
                <small class="text-truncate d-block" style="font-size: 11px; max-width: 80px;">{{ $story['username']
                    }}</small>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-3">
        <div class="row justify-content-center">
            <!-- Left Column - Main Feed -->
            <div class="col-lg-8">
                <!-- Create Post Card -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ auth()->user()->profile->avatar_url }}" alt="{{ auth()->user()->name }}"
                                class="rounded-circle me-3" style="width: 48px; height: 48px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <div class="input-group">
                                    <input type="text" class="form-control border-0 bg-light"
                                        placeholder="What's on your mind, {{ auth()->user()->name }}?"
                                        style="border-radius: 20px;"
                                        onclick="location.href='{{ route('posts.create') }}'">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between border-top pt-3">
                            <button class="btn btn-outline-secondary border-0 d-flex align-items-center"
                                onclick="location.href='{{ route('posts.create') }}?type=image'">
                                <i class="bi bi-image-fill text-success fs-5 me-2"></i>
                                <span>Photo</span>
                            </button>
                            <button class="btn btn-outline-secondary border-0 d-flex align-items-center"
                                onclick="location.href='{{ route('posts.create') }}?type=video'">
                                <i class="bi bi-camera-reels-fill text-danger fs-5 me-2"></i>
                                <span>Video</span>
                            </button>
                            <button class="btn btn-outline-secondary border-0 d-flex align-items-center"
                                onclick="location.href='{{ route('posts.create') }}?type=code'">
                                <i class="bi bi-code-slash text-info fs-5 me-2"></i>
                                <span>Code</span>
                            </button>
                            <button class="btn btn-outline-secondary border-0 d-flex align-items-center"
                                onclick="location.href='{{ route('posts.create') }}?type=article'">
                                <i class="bi bi-file-text-fill text-warning fs-5 me-2"></i>
                                <span>Article</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Feed Tabs -->
                <!-- Replace the feed tabs section with this improved version -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body p-2">
                        <ul class="nav nav-pills nav-fill" style="gap: 2px;">
                            <li class="nav-item flex-fill">
                                <a class="nav-link rounded-pill {{ ($activeTab ?? '') == '' ? 'active bg-primary' : 'bg-light text-dark' }}"
                                    href="{{ route('home') }}">
                                    <i class="bi bi-lightning me-1"></i> For You
                                </a>
                            </li>
                            <li class="nav-item flex-fill">
                                <a class="nav-link rounded-pill {{ ($activeTab ?? '') == 'following' ? 'active bg-primary' : 'bg-light text-dark' }}"
                                    href="{{ route('feed.following') }}">
                                    <i class="bi bi-people me-1"></i> Following
                                </a>
                            </li>
                            <li class="nav-item flex-fill">
                                <a class="nav-link rounded-pill {{ ($activeTab ?? '') == 'popular' ? 'active bg-primary' : 'bg-light text-dark' }}"
                                    href="{{ route('feed.popular') }}">
                                    <i class="bi bi-fire me-1"></i> Popular
                                </a>
                            </li>
                            <li class="nav-item flex-fill">
                                <a class="nav-link rounded-pill {{ ($activeTab ?? '') == 'latest' ? 'active bg-primary' : 'bg-light text-dark' }}"
                                    href="{{ route('feed.latest') }}">
                                    <i class="bi bi-clock me-1"></i> Latest
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Posts Feed -->
                @forelse($posts as $post)
                <div class="card mb-4 border-0 shadow-sm">
                    <!-- Post Header -->
                    <div class="card-body p-3 pb-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <a href="{{ route('profile.show', $post->user->profile->username) }}"
                                    class="text-decoration-none">
                                    <div class="position-relative">
                                        <img src="{{ $post->user->profile->avatar_url }}" alt="{{ $post->user->name }}"
                                            class="rounded-circle me-3"
                                            style="width: 42px; height: 42px; object-fit: cover;">
                                        @if($post->user->is_verified)
                                        <span
                                            class="position-absolute top-0 start-100 translate-middle bg-primary rounded-circle d-flex align-items-center justify-content-center border border-2 border-white"
                                            style="width: 16px; height: 16px;">
                                            <i class="bi bi-check text-white" style="font-size: 10px;"></i>
                                        </span>
                                        @endif
                                    </div>
                                </a>

                                <div>
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('profile.show', $post->user->profile->username) }}"
                                            class="text-decoration-none text-dark fw-bold me-2">
                                            {{ $post->user->profile->username }}
                                        </a>

                                        @if($post->visibility === 'private')
                                        <span class="badge bg-secondary" style="font-size: 10px; padding: 2px 6px;">
                                            <i class="bi bi-lock-fill me-1"></i>Private
                                        </span>
                                        @elseif($post->visibility === 'followers')
                                        <span class="badge bg-info" style="font-size: 10px; padding: 2px 6px;">
                                            <i class="bi bi-people-fill me-1"></i>Followers
                                        </span>
                                        @endif

                                        @if($post->is_pinned)
                                        <span class="badge bg-warning ms-1" style="font-size: 10px; padding: 2px 6px;">
                                            <i class="bi bi-pin-angle-fill me-1"></i>Pinned
                                        </span>
                                        @endif
                                    </div>

                                    <div class="d-flex align-items-center text-muted" style="font-size: 12px;">
                                        <span>{{ $post->created_at->diffForHumans() }}</span>
                                        <span class="mx-1">•</span>
                                        <span><i class="bi bi-{{ $post->type_icon }} me-1"></i>{{ $post->type_label
                                            }}</span>
                                        @if($post->type === 'article')
                                        <span class="mx-1">•</span>
                                        <span>{{ $post->formatted_reading_time }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="dropdown">
                                <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots fs-5"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    @if($post->user_id === auth()->id())
                                    <li><a class="dropdown-item" href="{{ route('posts.edit', $post) }}"><i
                                                class="bi bi-pencil me-2"></i>Edit</a></li>
                                    <li>
                                        <form action="{{ route('posts.pin', $post) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="bi bi-pin-angle me-2"></i>{{ $post->is_pinned ? 'Unpin' :
                                                'Pin' }}
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form action="{{ route('posts.destroy', $post) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger"
                                                onclick="return confirm('Delete this post?')">
                                                <i class="bi bi-trash me-2"></i>Delete
                                            </button>
                                        </form>
                                    </li>
                                    @else
                                    @if(auth()->user()->isFollowing($post->user))
                                    <li>
                                        <form action="{{ route('users.unfollow', $post->user) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="dropdown-item"><i
                                                    class="bi bi-person-dash me-2"></i>Unfollow</button>
                                        </form>
                                    </li>
                                    @else
                                    <li>
                                        <form action="{{ route('users.follow', $post->user) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item"><i
                                                    class="bi bi-person-plus me-2"></i>Follow</button>
                                        </form>
                                    </li>
                                    @endif
                                    <li><button class="dropdown-item" onclick="copyToClipboard('{{ $post->url }}')"><i
                                                class="bi bi-link-45deg me-2"></i>Copy Link</button></li>
                                    <li><button class="dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#shareModal-{{ $post->id }}"><i
                                                class="bi bi-share me-2"></i>Share</button></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><button class="dropdown-item text-danger" data-bs-toggle="modal"
                                            data-bs-target="#reportModal-{{ $post->id }}"><i
                                                class="bi bi-flag me-2"></i>Report</button></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{--
                    <!-- Post Content -->
                    <div class="card-body p-3 pt-0">
                        @if($post->title)
                        <h5 class="fw-bold mb-2">{{ $post->title }}</h5>
                        @endif

                        @if($post->content)
                        <div class="mb-3">
                            <div class="post-content">
                                {!! Str::markdown($post->content) !!}
                            </div>
                        </div>
                        @endif

                        <!-- Code Snippet -->
                        @if($post->type === 'code' && $post->code_snippet)
                        <div class="mb-3">
                            <div class="bg-dark text-light rounded">
                                <div
                                    class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom border-secondary">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-code-slash me-2"></i>
                                        <span class="badge bg-primary">{{ $post->code_language ?? 'Code' }}</span>
                                        @if($post->code_language)
                                        <span class="ms-2 text-light" style="font-size: 12px;">{{ $post->code_language
                                            }}</span>
                                        @endif
                                    </div>
                                    <button class="btn btn-sm btn-outline-light" onclick="copyCode('{{ $post->id }}')">
                                        <i class="bi bi-clipboard me-1"></i>Copy
                                    </button>
                                </div>
                                <pre class="mb-0 p-3"
                                    style="max-height: 400px; overflow: auto;"><code id="code-{{ $post->id }}">{{ $post->code_snippet }}</code></pre>
                            </div>
                        </div>
                        @endif

                        <!-- Image -->
                        @if($post->type === 'image' && $post->image_url)
                        <div class="mb-3">
                            <img src="{{ $post->image_url }}" alt="Post image" class="img-fluid w-100 rounded"
                                style="max-height: 600px; object-fit: contain; cursor: pointer;"
                                onclick="openImageModal('{{ $post->image_url }}', '{{ $post->title }}')">
                        </div>
                        @endif

                        <!-- Video -->
                        @if($post->type === 'video' && $post->video_path)
                        <div class="mb-3">
                            <div class="ratio ratio-16x9">
                                <video controls class="rounded" style="background-color: #000;">
                                    <source src="{{ Storage::url($post->video_path) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                            @if($post->content)
                            <div class="mt-2">
                                <p class="mb-0">{{ $post->content }}</p>
                            </div>
                            @endif
                        </div>
                        @endif

                        <!-- Tags -->
                        @if($post->tags->count() > 0)
                        <div class="mb-3">
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($post->tags as $tag)
                                <a href="{{ route('tags.show', $tag->slug) }}"
                                    class="badge bg-light text-dark text-decoration-none border">
                                    <i class="bi bi-hash"></i>{{ $tag->name }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    --}}
                    <!-- Post Content -->
                    <div class="card-body p-3 pt-0">
                        <!-- Regular Post Display -->
                        @if($post->type !== 'share')
                        @if($post->title)
                        <h5 class="fw-bold mb-2">{{ $post->title }}</h5>
                        @endif

                        @if($post->content)
                        <div class="mb-3">
                            <div class="post-content">
                                {!! Str::markdown($post->content) !!}
                            </div>
                        </div>
                        @endif

                        <!-- Code Snippet -->
                        @if($post->type === 'code' && $post->code_snippet)
                        <div class="mb-3">
                            <div class="bg-dark text-light rounded">
                                <div
                                    class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom border-secondary">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-code-slash me-2"></i>
                                        <span class="badge bg-primary">{{ $post->code_language ?? 'Code' }}</span>
                                        @if($post->code_language)
                                        <span class="ms-2 text-light" style="font-size: 12px;">{{ $post->code_language
                                            }}</span>
                                        @endif
                                    </div>
                                    <button class="btn btn-sm btn-outline-light" onclick="copyCode('{{ $post->id }}')">
                                        <i class="bi bi-clipboard me-1"></i>Copy
                                    </button>
                                </div>
                                <pre class="mb-0 p-3" style="max-height: 400px; overflow: auto;">
                    <code id="code-{{ $post->id }}">{{ $post->code_snippet }}</code>
                </pre>
                            </div>
                        </div>
                        @endif

                        <!-- Image -->
                        @if($post->type === 'image' && $post->image_url)
                        <div class="mb-3">
                            <img src="{{ $post->image_url }}" alt="Post image" class="img-fluid w-100 rounded"
                                style="max-height: 600px; object-fit: contain; cursor: pointer;"
                                onclick="openImageModal('{{ $post->image_url }}', '{{ $post->title }}')">
                        </div>
                        @endif

                        <!-- Video -->
                        @if($post->type === 'video' && $post->video_path)
                        <div class="mb-3">
                            <div class="ratio ratio-16x9">
                                <video controls class="rounded" style="background-color: #000;">
                                    <source src="{{ Storage::url($post->video_path) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                            @if($post->content)
                            <div class="mt-2">
                                <p class="mb-0">{{ $post->content }}</p>
                            </div>
                            @endif
                        </div>
                        @endif

                        <!-- Tags -->
                        @if($post->tags->count() > 0)
                        <div class="mb-3">
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($post->tags as $tag)
                                <a href="{{ route('tags.show', $tag->slug) }}"
                                    class="badge bg-light text-dark text-decoration-none border">
                                    <i class="bi bi-hash"></i>{{ $tag->name }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @else
                        <!-- Shared Post Display -->
                        <div class="shared-post-header mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <i class="bi bi-share-fill text-primary me-2"></i>
                                <span class="text-muted small">
                                    @if($post->user_id === auth()->id())
                                    You shared this post
                                    @else
                                    {{ $post->user->name }} shared this post
                                    @endif
                                </span>
                            </div>

                            <!-- Sharing User's Comment -->
                            @if($post->content)
                            <div class="mb-3">
                                <p class="mb-0">{{ $post->content }}</p>
                            </div>
                            @endif

                            <!-- Original Post Preview -->
                            @if($post->share_details)
                            <div class="card border" style="background-color: #f8f9fa;">
                                <div class="card-body">
                                    <!-- Original Post Header -->
                                    <div class="d-flex align-items-center mb-2">
                                        <img src="{{ $post->share_details['original_avatar'] ?? '' }}"
                                            alt="{{ $post->share_details['original_user_name'] ?? '' }}"
                                            class="rounded-circle me-2"
                                            style="width: 32px; height: 32px; object-fit: cover;">
                                        <div>
                                            <div class="fw-bold">{{ $post->share_details['original_username'] ?? 'User'
                                                }}</div>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($post->share_details['original_created_at'] ??
                                                now())->diffForHumans() }}
                                                • <i
                                                    class="bi bi-{{ $post->share_details['original_post_type'] === 'code' ? 'code-slash' : ($post->share_details['original_post_type'] === 'image' ? 'image' : ($post->share_details['original_post_type'] === 'video' ? 'play-circle' : 'file-text')) }}"></i>
                                                {{ ucfirst($post->share_details['original_post_type'] ?? 'post') }}
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Original Post Title -->
                                    @if(!empty($post->share_details['original_title']))
                                    <h6 class="fw-bold mb-2">{{ $post->share_details['original_title'] }}</h6>
                                    @endif

                                    <!-- Original Post Content -->
                                    @if(!empty($post->share_details['original_content']))
                                    <div class="mb-2">
                                        <div class="post-content">
                                            {!! Str::markdown($post->share_details['original_content']) !!}
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Original Code Snippet -->
                                    @if($post->share_details['original_post_type'] === 'code' &&
                                    !empty($post->share_details['original_code_snippet']))
                                    <div class="mb-2">
                                        <div class="bg-dark text-light rounded">
                                            <div
                                                class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom border-secondary">
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-code-slash me-2"></i>
                                                    <span class="badge bg-primary">{{
                                                        $post->share_details['original_code_language'] ??
                                                        'Code' }}</span>
                                                </div>
                                            </div>
                                            <pre class="mb-0 p-3"
                                                style="max-height: 200px; overflow: auto; font-size: 12px;">
                                <code>{{ $post->share_details['original_code_snippet'] }}</code>
                            </pre>
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Original Image -->
                                    @if($post->share_details['original_post_type'] === 'image')
                                    @php
                                    $imageUrl = $post->share_details['original_image_url'] ??
                                    (!empty($post->share_details['original_image_path']) ?
                                    Storage::url($post->share_details['original_image_path']) :
                                    null);
                                    @endphp

                                    @if($imageUrl)
                                    <div class="mb-2">
                                        <div class="text-center">
                                            <img src="{{ $imageUrl }}" alt="Shared image" class="img-fluid rounded"
                                                style="max-height: 300px; object-fit: contain; cursor: pointer;"
                                                onclick="openImageModal('{{ $imageUrl }}', '{{ $post->share_details['original_title'] ?? 'Shared Image' }}')">
                                        </div>
                                    </div>
                                    @endif
                                    @endif

                                    <!-- Original Video -->
                                    @if($post->share_details['original_post_type'] === 'video')
                                    @php
                                    $videoUrl = $post->share_details['original_video_url'] ??
                                    (!empty($post->share_details['original_video_path']) ?
                                    Storage::url($post->share_details['original_video_path']) :
                                    null);
                                    @endphp

                                    @if($videoUrl)
                                    <div class="mb-2">
                                        <div class="ratio ratio-16x9">
                                            <video controls class="rounded"
                                                style="background-color: #000; max-height: 300px;">
                                                <source src="{{ $videoUrl }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                    </div>
                                    @endif
                                    @endif

                                    <!-- Original Tags -->
                                    @if(isset($post->share_details['tags']) && count($post->share_details['tags']) > 0)
                                    <div class="mt-2">
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($post->share_details['tags'] as $tag)
                                            <span class="badge bg-light text-dark border">
                                                <i class="bi bi-hash"></i>{{ $tag }}
                                            </span>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif

                                    <!-- View Original Post Link -->
                                    <div class="mt-2 pt-2 border-top">
                                        <a href="{{ $post->link_url ?? '#' }}" class="text-decoration-none small">
                                            <i class="bi bi-arrow-up-right-square me-1"></i> View original post
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                    <!-- Post Stats & Actions -->
                    <div class="card-body p-3 pt-0">
                        <!-- Stats -->
                        <div class="d-flex justify-content-between text-muted small mb-2">
                            <div class="d-flex gap-3">
                                <span><i class="bi bi-eye me-1"></i>{{ $post->views_count }}</span>
                                <span class="comments-count"><i class="bi bi-chat me-1"></i>{{ $post->comments_count
                                    }}</span>
                                <span class="likes-count"><i class="bi bi-heart me-1"></i>{{ $post->likes_count
                                    }}</span>
                            </div>
                            <div>
                                <span><i class="bi bi-bookmark me-1"></i>{{ $post->saves->count() }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between border-top border-bottom py-2">
                            <button class="btn btn-outline-secondary border-0 d-flex align-items-center like-btn"
                                data-post-id="{{ $post->id }}" data-liked="{{ $post->is_liked ? 'true' : 'false' }}">
                                <i class="bi bi-heart{{ $post->is_liked ? '-fill text-danger' : '' }} fs-5 me-2"></i>
                                <span>Like</span>
                            </button>

                            <button class="btn btn-outline-secondary border-0 d-flex align-items-center comment-toggle"
                                data-post-id="{{ $post->id }}">
                                <i class="bi bi-chat fs-5 me-2"></i>
                                <span>Comment</span>
                            </button>

                            <button class="btn btn-outline-secondary border-0 d-flex align-items-center"
                                data-bs-toggle="modal" data-bs-target="#shareModal-{{ $post->id }}">
                                <i class="bi bi-share fs-5 me-2"></i>
                                <span>Share</span>
                            </button>

                            <button class="btn btn-outline-secondary border-0 d-flex align-items-center save-btn"
                                data-post-id="{{ $post->id }}" data-saved="{{ $post->is_saved ? 'true' : 'false' }}">
                                <i
                                    class="bi bi-bookmark{{ $post->is_saved ? '-fill text-warning' : '' }} fs-5 me-2"></i>
                                <span>Save</span>
                            </button>
                        </div>

                        <!-- Comments Section -->
                        <div id="comments-{{ $post->id }}" class="collapse">
                            <div class="pt-3">
                                <!-- Comments List -->
                                <div id="comments-list-{{ $post->id }}">
                                    @foreach($post->comments->take(3) as $comment)
                                    <div class="d-flex mb-2">
                                        <img src="{{ $comment->user->profile->avatar_url }}"
                                            alt="{{ $comment->user->name }}" class="rounded-circle me-2"
                                            style="width: 32px; height: 32px; object-fit: cover;">
                                        <div class="flex-grow-1">
                                            <div class="bg-light rounded p-2">
                                                <div class="d-flex justify-content-between">
                                                    <a href="{{ route('profile.show', $comment->user->profile->username) }}"
                                                        class="text-decoration-none fw-bold text-dark">
                                                        {{ $comment->user->profile->username }}
                                                    </a>
                                                    <small class="text-muted">{{ $comment->created_at->diffForHumans()
                                                        }}</small>
                                                </div>
                                                <p class="mb-0">{{ $comment->content }}</p>
                                            </div>
                                            <div class="d-flex align-items-center mt-1" style="font-size: 12px;">
                                                <button class="btn btn-link text-dark p-0 me-2 comment-like-btn"
                                                    data-comment-id="{{ $comment->id }}">
                                                    <i class="bi bi-heart"></i> Like
                                                </button>
                                                <button class="btn btn-link text-dark p-0 reply-toggle"
                                                    data-comment-id="{{ $comment->id }}">
                                                    Reply
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach

                                    @if($post->comments_count > 3)
                                    <div class="text-center mt-2">
                                        <button class="btn btn-link text-primary"
                                            onclick="loadMoreComments({{ $post->id }})">
                                            View all {{ $post->comments_count }} comments
                                        </button>
                                    </div>
                                    @endif
                                </div>

                                <!-- Add Comment Form -->
                                <div class="mt-3">
                                    <form action="{{ route('comments.store', $post) }}" method="POST"
                                        class="comment-form">
                                        @csrf
                                        <div class="input-group">
                                            <input type="text" class="form-control border-0 bg-light"
                                                placeholder="Write a comment..." name="content"
                                                style="border-radius: 20px;">
                                            <button class="btn btn-primary" type="submit">
                                                <i class="bi bi-send"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <!-- Empty State -->
                <div class="card border-0 shadow-sm text-center py-5">
                    <div class="card-body">
                        <i class="bi bi-newspaper display-1 text-muted mb-3"></i>
                        <h4 class="text-muted mb-3">Your feed is empty</h4>
                        <p class="text-muted mb-4">
                            Follow other developers or create your first post to get started!
                        </p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ route('explore') }}" class="btn btn-primary">
                                <i class="bi bi-compass me-2"></i> Explore
                            </a>
                            <a href="{{ route('posts.create') }}" class="btn btn-outline-primary">
                                <i class="bi bi-plus-circle me-2"></i> Create Post
                            </a>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Right Column - Sidebar -->
            <div class="col-lg-4 d-none d-lg-block">
                <!-- Current User Profile -->
                <div class="card mb-4 border-0 shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ auth()->user()->profile->avatar_url }}" alt="{{ auth()->user()->name }}"
                                class="rounded-circle me-3" style="width: 56px; height: 56px; object-fit: cover;">
                            <div>
                                <a href="{{ route('profile.show', auth()->user()->profile->username) }}"
                                    class="text-decoration-none text-dark fw-bold d-block">
                                    {{ auth()->user()->profile->username }}
                                </a>
                                <small class="text-muted">{{ auth()->user()->name }}</small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-around text-center border-top border-bottom py-3">
                            <div>
                                <div class="fw-bold">{{ auth()->user()->posts->count() ?? 0 }}</div>
                                <small class="text-muted">Posts</small>
                            </div>
                            <div>
                                <div class="fw-bold">{{ auth()->user()->followers->count() ?? 0 }}</div>
                                <small class="text-muted">Followers</small>
                            </div>
                            <div>
                                <div class="fw-bold">{{ auth()->user()->following->count() ?? 0 }}</div>
                                <small class="text-muted">Following</small>
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary w-100">
                                <i class="bi bi-pencil me-2"></i> Edit Profile
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Suggested Developers -->
                @if(isset($suggestedUsers) && $suggestedUsers->count() > 0)
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0">Suggested Developers</h6>
                            <a href="{{ route('explore') }}" class="text-decoration-none small">See All</a>
                        </div>

                        @foreach($suggestedUsers as $user)
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $user->profile->avatar_url }}" alt="{{ $user->name }}"
                                class="rounded-circle me-3" style="width: 40px; height: 40px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <a href="{{ route('profile.show', $user->profile->username) }}"
                                    class="text-decoration-none fw-bold text-dark d-block">
                                    {{ $user->profile->username }}
                                </a>
                                <small class="text-muted">{{ $user->followers->count() ?? 0 }} followers</small>
                            </div>
                            <form action="{{ route('users.follow', $user) }}" method="POST" class="follow-form">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm" style="font-size: 12px;">
                                    Follow
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Trending Tags -->
                @if(isset($trendingTags) && $trendingTags->count() > 0)
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0">Trending Topics</h6>
                            <a href="{{ route('tech.trending') }}" class="text-decoration-none small">See All</a>
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            @foreach($trendingTags as $tag)
                            <a href="{{ route('tags.show', $tag->slug) }}"
                                class="badge bg-light text-dark text-decoration-none border">
                                #{{ $tag->name }}
                                <span class="badge bg-secondary rounded-pill ms-1">{{ $tag->posts_count }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Platform Info -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">About DevDoko</h6>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <a href="#" class="text-decoration-none small text-muted">About</a>
                            <a href="#" class="text-decoration-none small text-muted">Help</a>
                            <a href="#" class="text-decoration-none small text-muted">API</a>
                            <a href="#" class="text-decoration-none small text-muted">Jobs</a>
                            <a href="#" class="text-decoration-none small text-muted">Privacy</a>
                            <a href="#" class="text-decoration-none small text-muted">Terms</a>
                            <a href="#" class="text-decoration-none small text-muted">Locations</a>
                        </div>
                        <div class="text-muted small">
                            © {{ date('Y') }} DevDoko
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Share Modal Template -->
<div class="modal fade" id="shareModal-template" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Share Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Share URL</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="shareUrl-template" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard(this)">
                            <i class="bi bi-clipboard"></i>
                        </button>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Share with message</label>
                    <form action="#" method="POST">
                        @csrf
                        <textarea class="form-control mb-2" name="content" rows="3"
                            placeholder="Add a message (optional)"></textarea>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-send me-1"></i> Share Post
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Report Modal Template -->
<div class="modal fade" id="reportModal-template" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Report Post</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="#" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Reason for reporting</label>
                        <select class="form-select" name="reason" required>
                            <option value="">Select a reason</option>
                            <option value="spam">Spam</option>
                            <option value="harassment">Harassment or bullying</option>
                            <option value="hate_speech">Hate speech or symbols</option>
                            <option value="violence">Violence or dangerous organizations</option>
                            <option value="false_info">False information</option>
                            <option value="intellectual_property">Intellectual property violation</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Additional details (optional)</label>
                        <textarea class="form-control" name="details" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Submit Report</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-header border-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <img id="modalImage" src="" class="img-fluid" alt="">
            </div>
        </div>
    </div>
</div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Like functionality
        document.querySelectorAll('.like-btn').forEach(button => {
            button.addEventListener('click', async function(e) {
                e.preventDefault();
                const postId = this.dataset.postId;
                const isLiked = this.dataset.liked === 'true';
                const icon = this.querySelector('i');
                const buttonText = this.querySelector('span');

                try {
                    const response = await fetch(`/posts/${postId}/like/toggle`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();

                        // Update UI
                        if (data.liked) {
                            icon.classList.remove('bi-heart');
                            icon.classList.add('bi-heart-fill', 'text-danger');
                            this.dataset.liked = 'true';
                            buttonText.textContent = 'Liked';
                        } else {
                            icon.classList.remove('bi-heart-fill', 'text-danger');
                            icon.classList.add('bi-heart');
                            this.dataset.liked = 'false';
                            buttonText.textContent = 'Like';
                        }

                        // Update like count display
                        const postCard = this.closest('.card');
                        const likesCountElement = postCard.querySelector('.likes-count');
                        if (likesCountElement) {
                            const currentCount = parseInt(likesCountElement.textContent.match(/\d+/)[0]);
                            likesCountElement.innerHTML = `<i class="bi bi-heart me-1"></i>${data.liked ? currentCount + 1 : currentCount - 1}`;
                        }

                        // Animation
                        icon.style.transform = 'scale(1.2)';
                        setTimeout(() => {
                            icon.style.transform = 'scale(1)';
                        }, 200);
                    } else {
                        console.error('Failed to like post');
                        alert('Failed to like the post. Please try again.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Failed to like the post. Please try again.');
                }
            });
        });

        // Save post functionality
        document.querySelectorAll('.save-btn').forEach(button => {
            button.addEventListener('click', async function(e) {
                e.preventDefault();
                const postId = this.dataset.postId;
                const isSaved = this.dataset.saved === 'true';
                const icon = this.querySelector('i');
                const buttonText = this.querySelector('span');

                const url = `/posts/${postId}/save`;
                const method = isSaved ? 'DELETE' : 'POST';

                try {
                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();

                        // Update UI
                        if (data.saved) {
                            icon.classList.remove('bi-bookmark');
                            icon.classList.add('bi-bookmark-fill', 'text-warning');
                            this.dataset.saved = 'true';
                            buttonText.textContent = 'Saved';
                        } else {
                            icon.classList.remove('bi-bookmark-fill', 'text-warning');
                            icon.classList.add('bi-bookmark');
                            this.dataset.saved = 'false';
                            buttonText.textContent = 'Save';
                        }

                        // Animation
                        icon.style.transform = 'scale(1.2)';
                        setTimeout(() => {
                            icon.style.transform = 'scale(1)';
                        }, 200);
                    } else {
                        console.error('Failed to save post');
                        alert('Failed to save the post. Please try again.');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Failed to save the post. Please try again.');
                }
            });
        });

        // Comment toggle functionality
        document.querySelectorAll('.comment-toggle').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const postId = this.dataset.postId;
                const commentsSection = document.getElementById(`comments-${postId}`);

                if (commentsSection) {
                    const bsCollapse = new bootstrap.Collapse(commentsSection, {
                        toggle: true
                    });

                    // Focus on comment input when opened
                    commentsSection.addEventListener('shown.bs.collapse', function() {
                        const input = this.querySelector('input[name="content"]');
                        if (input) input.focus();
                    });
                }
            });
        });

        // Comment form submission
        document.querySelectorAll('.comment-form').forEach(form => {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const input = this.querySelector('input[name="content"]');
                const postId = this.action.split('/').filter(part => !isNaN(part))[0];
                const commentsList = document.getElementById(`comments-list-${postId}`);

                // Validate input
                if (!input.value.trim()) {
                    alert('Please enter a comment');
                    return;
                }

                try {
                    const response = await fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        input.value = '';

                        // Add new comment to the list
                        const commentHtml = `
                            <div class="d-flex mb-2">
                                <img src="${data.comment.user.profile.avatar_url}"
                                     alt="${data.comment.user.name}"
                                     class="rounded-circle me-2"
                                     style="width: 32px; height: 32px; object-fit: cover;">
                                <div class="flex-grow-1">
                                    <div class="bg-light rounded p-2">
                                        <div class="d-flex justify-content-between">
                                            <a href="/@${data.comment.user.profile.username}"
                                               class="text-decoration-none fw-bold text-dark">
                                                ${data.comment.user.profile.username}
                                            </a>
                                            <small class="text-muted">Just now</small>
                                        </div>
                                        <p class="mb-0">${data.comment.content}</p>
                                    </div>
                                    <div class="d-flex align-items-center mt-1" style="font-size: 12px;">
                                        <button class="btn btn-link text-dark p-0 me-2 comment-like-btn">
                                            <i class="bi bi-heart"></i> Like
                                        </button>
                                        <button class="btn btn-link text-dark p-0 reply-toggle">
                                            Reply
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;

                        if (commentsList) {
                            commentsList.insertAdjacentHTML('afterbegin', commentHtml);

                            // Update comment count
                            const postCard = this.closest('.card');
                            const commentCountElement = postCard.querySelector('.comments-count');
                            if (commentCountElement) {
                                const currentCount = parseInt(commentCountElement.textContent.match(/\d+/)[0]);
                                commentCountElement.innerHTML = `<i class="bi bi-chat me-1"></i>${currentCount + 1}`;
                            }
                        }

                        // Show success message
                        const alertDiv = document.createElement('div');
                        alertDiv.className = 'alert alert-success alert-dismissible fade show mt-2';
                        alertDiv.innerHTML = `
                            <i class="bi bi-check-circle-fill me-2"></i>
                            ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        `;
                        this.parentNode.insertBefore(alertDiv, this);

                        setTimeout(() => {
                            alertDiv.remove();
                        }, 3000);
                    } else {
                        const errorData = await response.json();
                        alert(errorData.errors?.content?.[0] || 'Failed to add comment');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Failed to add comment. Please try again.');
                }
            });
        });

        // Share button functionality
        document.querySelectorAll('[data-bs-target^="#shareModal-"]').forEach(button => {
            button.addEventListener('click', function() {
                const modalId = this.getAttribute('data-bs-target');
                const postId = modalId.match(/\d+/)[0];
                const shareUrl = `${window.location.origin}/posts/${postId}`;

                // Create modal if it doesn't exist
                if (!document.querySelector(modalId)) {
                    createShareModal(postId, shareUrl);
                }

                // Update share URL
                const modal = document.querySelector(modalId);
                if (modal) {
                    const urlInput = modal.querySelector(`#shareUrl-${postId}`);
                    if (urlInput) {
                        urlInput.value = shareUrl;
                    }

                    // Update form action
                    const form = modal.querySelector('form');
                    if (form) {
                        form.action = `/posts/${postId}/share`;
                    }
                }
            });
        });

        // Follow form submission
        document.querySelectorAll('.follow-form').forEach(form => {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const button = this.querySelector('button');
                const originalText = button.textContent;
                const originalClass = button.className;

                try {
                    const response = await fetch(this.action, {
                        method: 'POST',
                        body: new FormData(this),
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();

                        if (data.following) {
                            button.textContent = 'Following';
                            button.classList.remove('btn-primary');
                            button.classList.add('btn-outline-secondary');
                        } else {
                            button.textContent = 'Follow';
                            button.classList.remove('btn-outline-secondary');
                            button.classList.add('btn-primary');
                        }
                    } else {
                        button.textContent = originalText;
                        button.className = originalClass;
                        alert('Failed to follow user');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    button.textContent = originalText;
                    button.className = originalClass;
                    alert('Failed to follow user');
                }
            });
        });
    });

    // Create share modal dynamically
    // Enhanced share modal functionality
    function createShareModal(postId, shareUrl) {
    // Check if modal already exists
    if (document.getElementById(`shareModal-${postId}`)) {
    return;
    }

    const modalHtml = `
    <div class="modal fade" id="shareModal-${postId}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="bi bi-share me-2"></i>Share Post
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Share via link</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="shareUrl-${postId}" value="${shareUrl}" readonly>
                            <button class="btn btn-outline-primary" type="button" onclick="copyToClipboard(this)">
                                <i class="bi bi-clipboard"></i> Copy
                            </button>
                        </div>
                        <small class="text-muted">Share this link with others</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Quick share to platform</label>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-primary flex-fill"
                                onclick="shareToWhatsApp('${shareUrl}')">
                                <i class="bi bi-whatsapp"></i> WhatsApp
                            </button>
                            <button type="button" class="btn btn-outline-info flex-fill"
                                onclick="shareToTwitter('${shareUrl}')">
                                <i class="bi bi-twitter"></i> Twitter
                            </button>
                            <button type="button" class="btn btn-outline-primary flex-fill"
                                onclick="shareToFacebook('${shareUrl}')">
                                <i class="bi bi-facebook"></i> Facebook
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Share as a new post</label>
                        <form action="/posts/${postId}/share" method="POST" class="share-post-form">
                            @csrf
                            <div class="mb-2">
                                <textarea class="form-control" name="content" rows="3"
                                    placeholder="Add your thoughts about this post (optional)"></textarea>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">This will create a new post sharing this content</small>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send me-1"></i> Share Post
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    `;

    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Add form submission handler
    const form = document.querySelector(`#shareModal-${postId} .share-post-form`);
    if (form) {
    form.addEventListener('submit', function(e) {
    e.preventDefault();
    shareAsPost(this, postId);
    });
    }
    }

    // Social media sharing functions
    function shareToWhatsApp(url) {
    const text = encodeURIComponent("Check out this post on DevDoko: ");
    window.open(`https://wa.me/?text=${text} ${encodeURIComponent(url)}`, '_blank');
    }

    function shareToTwitter(url) {
    const text = encodeURIComponent("Check out this post on DevDoko!");
    window.open(`https://twitter.com/intent/tweet?text=${text}&url=${encodeURIComponent(url)}`, '_blank');
    }

    function shareToFacebook(url) {
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`, '_blank',
    'width=600,height=400');
    }

    // Share as post function
    async function shareAsPost(form, postId) {
    const formData = new FormData(form);
    const content = formData.get('content') || '';

    try {
    const response = await fetch(form.action, {
    method: 'POST',
    body: JSON.stringify({ content: content }),
    headers: {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    'Accept': 'application/json',
    'Content-Type': 'application/json'
    }
    });

    if (response.ok) {
    const data = await response.json();

    // Close the modal
    const modal = bootstrap.Modal.getInstance(document.getElementById(`shareModal-${postId}`));
    if (modal) {
    modal.hide();
    }

    // Show success message
    alert('Post shared successfully!');

    // Redirect to the shared post if URL is provided
    if (data.post_url) {
    setTimeout(() => {
    window.location.href = data.post_url;
    }, 1000);
    }
    } else {
    const errorData = await response.json();
    alert(errorData.message || 'Failed to share post');
    }
    } catch (error) {
    console.error('Error sharing post:', error);
    alert('Failed to share post. Please try again.');
    }
    }

    // Utility functions
    window.copyToClipboard = function(element) {
        let text;
        if (element.tagName === 'INPUT') {
            text = element.value;
        } else if (element.tagName === 'BUTTON') {
            text = element.closest('.input-group').querySelector('input').value;
        } else {
            text = element;
        }

        navigator.clipboard.writeText(text).then(() => {
            // Show success message
            const originalText = element.textContent || 'Copy';
            if (element.tagName === 'BUTTON') {
                element.innerHTML = '<i class="bi bi-check"></i> Copied!';
                element.classList.add('btn-success');

                setTimeout(() => {
                    element.innerHTML = originalText;
                    element.classList.remove('btn-success');
                }, 2000);
            } else {
                alert('Link copied to clipboard!');
            }
        }).catch(err => {
            console.error('Failed to copy: ', err);
            alert('Failed to copy to clipboard');
        });
    };

    window.copyCode = function(postId) {
        const codeElement = document.getElementById(`code-${postId}`);
        const code = codeElement.textContent;

        navigator.clipboard.writeText(code).then(() => {
            const button = event.target.closest('button');
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="bi bi-check"></i> Copied!';
            button.classList.add('btn-success');
            button.classList.remove('btn-outline-light');

            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.classList.remove('btn-success');
                button.classList.add('btn-outline-light');
            }, 2000);
        }).catch(err => {
            console.error('Failed to copy: ', err);
            alert('Failed to copy code');
        });
    };

    window.openImageModal = function(imageUrl, title) {
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imageUrl;
        modalImage.alt = title || 'Image';

        const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
        imageModal.show();
    };

    window.loadMoreComments = async function(postId) {
        try {
            const response = await fetch(`/posts/${postId}/comments?offset=3`);
            if (response.ok) {
                const data = await response.json();
                document.getElementById(`comments-list-${postId}`).innerHTML = data.html;
            }
        } catch (error) {
            console.error('Error:', error);
        }
    };
    // Comment like functionality
document.addEventListener('click', function(e) {
    if (e.target.closest('.comment-like-btn')) {
        e.preventDefault();
        const button = e.target.closest('.comment-like-btn');
        const commentId = button.dataset.commentId;

        if (!commentId) return;

        likeComment(commentId, button);
    }
});

async function likeComment(commentId, button) {
    try {
        const response = await fetch(`/comments/${commentId}/like`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        if (response.ok) {
            const data = await response.json();
            const icon = button.querySelector('i');

            if (data.liked) {
                icon.classList.remove('bi-heart');
                icon.classList.add('bi-heart-fill', 'text-danger');
            } else {
                icon.classList.remove('bi-heart-fill', 'text-danger');
                icon.classList.add('bi-heart');
            }

            // Animation
            icon.style.transform = 'scale(1.2)';
            setTimeout(() => {
                icon.style.transform = 'scale(1)';
            }, 200);
        }
    } catch (error) {
        console.error('Error liking comment:', error);
    }
}
// Expand/collapse shared post preview
window.toggleSharedPost = function(button) {
const preview = button.closest('.original-post-preview');
if (preview.classList.contains('expanded')) {
preview.classList.remove('expanded');
button.innerHTML = '<i class="bi bi-arrows-expand me-1"></i> Show more';
} else {
preview.classList.add('expanded');
button.innerHTML = '<i class="bi bi-arrows-collapse me-1"></i> Show less';
}
}

// View original post
window.viewOriginalPost = function(postId) {
window.open(`/posts/${postId}`, '_blank');
}

// Copy shared post link
window.copySharedPostLink = function(postId) {
const shareUrl = `${window.location.origin}/posts/${postId}`;
navigator.clipboard.writeText(shareUrl).then(() => {
alert('Post link copied to clipboard!');
}).catch(err => {
console.error('Failed to copy: ', err);
alert('Failed to copy link');
});
}
</script>