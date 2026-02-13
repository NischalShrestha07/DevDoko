{{-- resources/views/groups/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Create New Group - DevDoko')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-plus-circle me-2 text-primary"></i>
                        Create New Group
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('groups.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Group Name -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Group Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" placeholder="e.g., Laravel Developers" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                rows="4" placeholder="What is this group about?"
                                required>{{ old('description') }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category</label>
                            <select name="category" class="form-select @error('category') is-invalid @enderror"
                                required>
                                <option value="">Select a category</option>
                                @foreach($categories as $value => $label)
                                <option value="{{ $value }}" {{ old('category')==$value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                            @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tags -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tags</label>
                            <input type="text" name="tags" class="form-control @error('tags') is-invalid @enderror"
                                value="{{ old('tags') }}" placeholder="laravel, php, backend (comma separated)">
                            <div class="form-text">Add relevant tags to help people discover your group</div>
                            @error('tags')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Privacy & Approval -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Privacy</label>
                                <select name="privacy" class="form-select @error('privacy') is-invalid @enderror"
                                    required>
                                    <option value="public" {{ old('privacy')=='public' ? 'selected' : '' }}>🌍 Public
                                    </option>
                                    <option value="private" {{ old('privacy')=='private' ? 'selected' : '' }}>🔒 Private
                                    </option>
                                    <option value="hidden" {{ old('privacy')=='hidden' ? 'selected' : '' }}>👻 Hidden
                                    </option>
                                </select>
                                @error('privacy')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Member Approval</label>
                                <select name="member_approval"
                                    class="form-select @error('member_approval') is-invalid @enderror" required>
                                    <option value="anyone" {{ old('member_approval')=='anyone' ? 'selected' : '' }}>
                                        Anyone can join</option>
                                    <option value="admin_approval" {{ old('member_approval')=='admin_approval'
                                        ? 'selected' : '' }}>Admin approval required</option>
                                    <option value="invite_only" {{ old('member_approval')=='invite_only' ? 'selected'
                                        : '' }}>Invite only</option>
                                </select>
                                @error('member_approval')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Icons & Images -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Group Icon</label>
                                <input type="file" name="icon" class="form-control @error('icon') is-invalid @enderror"
                                    accept="image/*">
                                <div class="form-text">Square image recommended. Max 2MB.</div>
                                @error('icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Cover Image</label>
                                <input type="file" name="cover_image"
                                    class="form-control @error('cover_image') is-invalid @enderror" accept="image/*">
                                <div class="form-text">Recommended size: 1200x300px. Max 5MB.</div>
                                @error('cover_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('groups.index') }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Create Group
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection