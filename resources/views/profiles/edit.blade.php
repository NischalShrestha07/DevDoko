@extends('layouts.app')

@section('title', 'Edit Profile - DevDoko')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0">Edit Profile</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Profile Picture -->
                    <div class="text-center mb-4">
                        <div class="position-relative d-inline-block">
                            @if($profile->avatar)
                            <img src="{{ $profile->avatar_url }}" id="avatar-preview" class="rounded-circle mb-3"
                                width="150" height="150" style="object-fit:cover;">
                            @else
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mb-3"
                                id="avatar-preview" style="width: 150px; height: 150px;">
                                <i class="bi bi-person-fill text-white fs-1"></i>
                            </div>
                            @endif
                            <label for="avatar"
                                class="btn btn-primary btn-sm position-absolute bottom-0 end-0 rounded-circle">
                                <i class="bi bi-camera"></i>
                            </label>
                            <input type="file" class="form-control d-none" id="avatar" name="avatar" accept="image/*">
                        </div>
                        @error('avatar')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Username -->
                    <div class="mb-3">
                        <label for="username" class="form-label fw-bold">Username</label>
                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username"
                            name="username" value="{{ old('username', $profile->username) }}" required>
                        @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Name -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ auth()->user()->name }}">
                    </div>

                    <!-- Bio -->
                    <div class="mb-3">
                        <label for="bio" class="form-label fw-bold">Bio</label>
                        <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio"
                            rows="3">{{ old('bio', $profile->bio) }}</textarea>
                        @error('bio')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <!-- GitHub Link -->
                    <div class="mb-3">
                        <label for="github_link" class="form-label fw-bold">GitHub Profile</label>
                        <input type="url" class="form-control @error('github_link') is-invalid @enderror"
                            id="github_link" name="github_link" placeholder="https://github.com/username"
                            value="{{ old('github_link', $profile->github_link) }}">
                        @error('github_link')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Portfolio Link -->
                    <div class="mb-3">
                        <label for="portfolio_link" class="form-label fw-bold">Portfolio Website</label>
                        <input type="url" class="form-control @error('portfolio_link') is-invalid @enderror"
                            id="portfolio_link" name="portfolio_link" placeholder="https://yourportfolio.com"
                            value="{{ old('portfolio_link', $profile->portfolio_link) }}">
                        @error('portfolio_link')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tech Stack -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Tech Stack</label>
                        <div class="row">
                            @foreach($techTags as $techTag)
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="tech_tags[]"
                                        value="{{ $techTag->id }}" id="tech_tag_{{ $techTag->id }}" {{
                                        in_array($techTag->id, $userTechTags) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="tech_tag_{{ $techTag->id }}">
                                        {{ $techTag->name }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @error('tech_tags')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                        @error('tech_tags.*')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('profile.show', $profile->username) }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Avatar preview
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.getElementById('avatar-preview');

    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (avatarPreview.tagName === 'IMG') {
                        avatarPreview.src = e.target.result;
                    } else {
                        // Replace div with image
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'rounded-circle mb-3';
                        img.width = 150;
                        img.height = 150;
                        img.style.objectFit = 'cover';
                        img.id = 'avatar-preview';
                        avatarPreview.parentNode.replaceChild(img, avatarPreview);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endpush
@endsection