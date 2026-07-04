@extends('layouts.app')

@section('title', 'Edit Project - DevDoko')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-pencil me-2"></i>Edit Project</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('projects.update', $project) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-bold">Title</label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title', $project->title) }}" maxlength="200" required>
                            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Short Description</label>
                            <input type="text" name="short_description"
                                class="form-control @error('short_description') is-invalid @enderror"
                                value="{{ old('short_description', $project->short_description) }}" maxlength="300" required>
                            @error('short_description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" rows="6"
                                class="form-control @error('description') is-invalid @enderror"
                                maxlength="5000" required>{{ old('description', $project->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Repository URL</label>
                                <input type="url" name="repository_url"
                                    class="form-control @error('repository_url') is-invalid @enderror"
                                    value="{{ old('repository_url', $project->repository_url) }}">
                                @error('repository_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Live URL</label>
                                <input type="url" name="live_url"
                                    class="form-control @error('live_url') is-invalid @enderror"
                                    value="{{ old('live_url', $project->live_url) }}">
                                @error('live_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Category</label>
                                <input type="text" name="category"
                                    class="form-control @error('category') is-invalid @enderror"
                                    value="{{ old('category', $project->category) }}" maxlength="50" required>
                                @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Difficulty</label>
                                <select name="difficulty" class="form-select @error('difficulty') is-invalid @enderror" required>
                                    @foreach(['beginner', 'intermediate', 'advanced', 'expert'] as $level)
                                    <option value="{{ $level }}" {{ old('difficulty', $project->difficulty) === $level ? 'selected' : '' }}>
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
                                                {{ in_array($tech, old('technologies', $project->technologies ?? [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="tech-{{ $loop->index }}">{{ $tech }}</label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @error('technologies')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        @if($project->thumbnail_path)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Current Thumbnail</label>
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ $project->thumbnail_url }}" alt="Thumbnail" class="rounded" style="width: 120px; height: 80px; object-fit: cover;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remove_thumbnail" value="1" id="removeThumbnail">
                                    <label class="form-check-label" for="removeThumbnail">Remove thumbnail</label>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-bold">{{ $project->thumbnail_path ? 'Replace Thumbnail' : 'Thumbnail' }}</label>
                            <input type="file" name="thumbnail" accept="image/*"
                                class="form-control @error('thumbnail') is-invalid @enderror">
                            @error('thumbnail')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        @if(!empty($project->screenshots))
                        <div class="mb-3">
                            <label class="form-label fw-bold">Current Screenshots</label>
                            <div class="d-flex flex-wrap gap-3">
                                @foreach($project->screenshots as $screenshot)
                                <div class="text-center">
                                    <img src="{{ Storage::url($screenshot) }}" alt="Screenshot" class="rounded mb-1" style="width: 100px; height: 70px; object-fit: cover;">
                                    <div class="form-check small">
                                        <input class="form-check-input" type="checkbox" name="remove_screenshots[]" value="{{ $screenshot }}" id="rs-{{ $loop->index }}">
                                        <label class="form-check-label" for="rs-{{ $loop->index }}">Remove</label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label fw-bold">Add Screenshots (up to 5 total)</label>
                            <input type="file" name="screenshots[]" accept="image/*" multiple
                                class="form-control @error('screenshots') is-invalid @enderror">
                            @error('screenshots')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="is_public" value="1" id="isPublic"
                                {{ old('is_public', $project->is_public) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isPublic">Make this project public</label>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('projects.show', $project) }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
