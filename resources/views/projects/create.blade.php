@extends('layouts.app')

@section('title', 'Add Project - DevDoko')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-2"></i>Add New Project</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold">Title</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title') }}" maxlength="200" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Short Description</label>
                            <input type="text" name="short_description"
                                class="form-control @error('short_description') is-invalid @enderror"
                                value="{{ old('short_description') }}" maxlength="300" required>
                            @error('short_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" rows="6"
                                class="form-control @error('description') is-invalid @enderror"
                                maxlength="5000" required>{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Repository URL</label>
                                <input type="url" name="repository_url"
                                    class="form-control @error('repository_url') is-invalid @enderror"
                                    value="{{ old('repository_url') }}" placeholder="https://github.com/...">
                                @error('repository_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Live URL</label>
                                <input type="url" name="live_url"
                                    class="form-control @error('live_url') is-invalid @enderror"
                                    value="{{ old('live_url') }}" placeholder="https://example.com">
                                @error('live_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Category</label>
                                <input type="text" name="category"
                                    class="form-control @error('category') is-invalid @enderror"
                                    value="{{ old('category') }}" maxlength="50" required>
                                @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Difficulty</label>
                                <select name="difficulty" class="form-select @error('difficulty') is-invalid @enderror" required>
                                    <option value="">Select difficulty</option>
                                    @foreach(['beginner', 'intermediate', 'advanced', 'expert'] as $level)
                                    <option value="{{ $level }}" {{ old('difficulty') === $level ? 'selected' : '' }}>
                                        {{ ucfirst($level) }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('difficulty')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Technologies</label>
                            <div class="border rounded p-3 @error('technologies') is-invalid @enderror" style="max-height: 220px; overflow-y: auto;">
                                <div class="row row-cols-2 row-cols-md-3 g-2">
                                    @foreach($technologies as $tech)
                                    <div class="col">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="technologies[]"
                                                value="{{ $tech }}" id="tech-{{ $loop->index }}"
                                                {{ in_array($tech, old('technologies', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="tech-{{ $loop->index }}">{{ $tech }}</label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @error('technologies')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Thumbnail</label>
                            <input type="file" name="thumbnail" accept="image/*"
                                class="form-control @error('thumbnail') is-invalid @enderror">
                            @error('thumbnail')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Screenshots (up to 5)</label>
                            <input type="file" name="screenshots[]" accept="image/*" multiple
                                class="form-control @error('screenshots') is-invalid @enderror">
                            @error('screenshots')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="is_public" value="1" id="isPublic"
                                {{ old('is_public', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isPublic">Make this project public</label>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>Create Project
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
