{{-- resources/views/groups/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Group - ' . $group->name)

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="bi bi-gear me-2 text-primary"></i>
                        Edit Group: {{ $group->name }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('groups.update', $group->slug) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Current Icons Preview -->
                        <div class="row mb-4">
                            <div class="col-md-6 text-center">
                                <label class="form-label d-block fw-semibold">Current Icon</label>
                                @if($group->icon_url)
                                <img src="{{ $group->icon_url }}" class="rounded-circle border"
                                    style="width: 100px; height: 100px; object-fit: cover;">
                                <div class="mt-2">
                                    <div class="form-check">
                                        <input type="checkbox" name="remove_icon" class="form-check-input"
                                            id="removeIcon">
                                        <label class="form-check-label text-danger" for="removeIcon">Remove icon</label>
                                    </div>
                                </div>
                                @else
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center"
                                    style="width: 100px; height: 100px;">
                                    <i class="bi bi-people-fill text-muted fs-1"></i>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-6 text-center">
                                <label class="form-label d-block fw-semibold">Current Cover</label>
                                @if($group->cover_url)
                                <img src="{{ $group->cover_url }}" class="rounded"
                                    style="width: 200px; height: 80px; object-fit: cover;">
                                <div class="mt-2">
                                    <div class="form-check">
                                        <input type="checkbox" name="remove_cover" class="form-check-input"
                                            id="removeCover">
                                        <label class="form-check-label text-danger" for="removeCover">Remove
                                            cover</label>
                                    </div>
                                </div>
                                @else
                                <div class="bg-light rounded" style="width: 200px; height: 80px;"></div>
                                @endif
                            </div>
                        </div>

                        <!-- Rest of form fields (same as create) -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Group Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $group->name) }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                rows="4" required>{{ old('description', $group->description) }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Category</label>
                            <select name="category" class="form-select @error('category') is-invalid @enderror"
                                required>
                                @foreach($categories as $value => $label)
                                <option value="{{ $value }}" {{ old('category', $group->category) == $value ? 'selected'
                                    : '' }}>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                            @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tags</label>
                            <input type="text" name="tags" class="form-control @error('tags') is-invalid @enderror"
                                value="{{ old('tags', is_array($group->tags) ? implode(', ', $group->tags) : '') }}"
                                placeholder="laravel, php, backend (comma separated)">
                            @error('tags')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Privacy</label>
                                <select name="privacy" class="form-select @error('privacy') is-invalid @enderror"
                                    required>
                                    <option value="public" {{ old('privacy', $group->privacy) == 'public' ? 'selected' :
                                        '' }}>🌍 Public</option>
                                    <option value="private" {{ old('privacy', $group->privacy) == 'private' ? 'selected'
                                        : '' }}>🔒 Private</option>
                                    <option value="hidden" {{ old('privacy', $group->privacy) == 'hidden' ? 'selected' :
                                        '' }}>👻 Hidden</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Member Approval</label>
                                <select name="member_approval"
                                    class="form-select @error('member_approval') is-invalid @enderror" required>
                                    <option value="anyone" {{ old('member_approval', $group->member_approval) ==
                                        'anyone' ? 'selected' : '' }}>Anyone can join</option>
                                    <option value="admin_approval" {{ old('member_approval', $group->member_approval) ==
                                        'admin_approval' ? 'selected' : '' }}>Admin approval required</option>
                                    <option value="invite_only" {{ old('member_approval', $group->member_approval) ==
                                        'invite_only' ? 'selected' : '' }}>Invite only</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">New Icon (optional)</label>
                                <input type="file" name="icon" class="form-control @error('icon') is-invalid @enderror"
                                    accept="image/*">
                                @error('icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">New Cover (optional)</label>
                                <input type="file" name="cover_image"
                                    class="form-control @error('cover_image') is-invalid @enderror" accept="image/*">
                                @error('cover_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('groups.show', $group->slug) }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Update Group
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection