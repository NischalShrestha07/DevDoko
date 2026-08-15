<form action="{{ $route }}" method="POST">
    @csrf
    @method($method)

    @if($errors->any())
        <div class="alert alert-danger border-0 shadow-sm">
            <div class="d-flex align-items-center mb-2">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <span class="fw-semibold">Please fix the following errors:</span>
            </div>
            <ul class="mb-0 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-12">
            <label for="company_name" class="form-label fw-semibold">Company Name <span class="text-danger">*</span></label>
            <input type="text" name="company_name" id="company_name"
                class="form-control @error('company_name') is-invalid @enderror"
                value="{{ old('company_name', $job->company_name ?? '') }}" placeholder="e.g. Acme Corp">
            @error('company_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12">
            <label for="title" class="form-label fw-semibold">Job Title <span class="text-danger">*</span></label>
            <input type="text" name="title" id="title"
                class="form-control @error('title') is-invalid @enderror"
                value="{{ old('title', $job->title ?? '') }}" placeholder="e.g. Senior Laravel Developer">
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12">
            <label for="description" class="form-label fw-semibold">Job Description <span class="text-danger">*</span></label>
            <textarea name="description" id="description" rows="8"
                class="form-control @error('description') is-invalid @enderror"
                placeholder="Describe the role, responsibilities, requirements, and benefits...">{{ old('description', $job->description ?? '') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="type" class="form-label fw-semibold">Employment Type <span class="text-danger">*</span></label>
            <select name="type" id="type" class="form-select @error('type') is-invalid @enderror">
                <option value="">Select type</option>
                @foreach($types as $type)
                    <option value="{{ $type }}" {{ old('type', $job->type ?? '') === $type ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach
            </select>
            @error('type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="experience_level" class="form-label fw-semibold">Experience Level <span class="text-danger">*</span></label>
            <select name="experience_level" id="experience_level" class="form-select @error('experience_level') is-invalid @enderror">
                <option value="">Select level</option>
                @foreach($experienceLevels as $level)
                    <option value="{{ $level }}" {{ old('experience_level', $job->experience_level ?? '') === $level ? 'selected' : '' }}>{{ $level }}</option>
                @endforeach
            </select>
            @error('experience_level')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="location_type" class="form-label fw-semibold">Location Type <span class="text-danger">*</span></label>
            <select name="location_type" id="location_type" class="form-select @error('location_type') is-invalid @enderror">
                <option value="">Select location type</option>
                @foreach($locationTypes as $locType)
                    <option value="{{ $locType }}" {{ old('location_type', $job->location_type ?? '') === $locType ? 'selected' : '' }}>{{ $locType }}</option>
                @endforeach
            </select>
            @error('location_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6">
            <label for="location" class="form-label fw-semibold">Location <span class="app-text-muted fw-normal">(optional)</span></label>
            <input type="text" name="location" id="location"
                class="form-control @error('location') is-invalid @enderror"
                value="{{ old('location', $job->location ?? '') }}" placeholder="e.g. Kathmandu, Nepal">
            @error('location')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-12">
            <label for="required_skills" class="form-label fw-semibold">
                Required Skills <span class="app-text-muted fw-normal">(comma separated)</span>
            </label>
            <input type="text" name="required_skills" id="required_skills"
                class="form-control @error('required_skills') is-invalid @enderror"
                value="{{ old('required_skills', is_array($job->required_skills ?? null) ? implode(', ', $job->required_skills) : ($job->required_skills ?? '')) }}"
                placeholder="e.g. Laravel, Vue.js, MySQL, Docker">
            @error('required_skills')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="form-text">Enter skills separated by commas</div>
        </div>

        <div class="col-md-6">
            <label for="company_website" class="form-label fw-semibold">Company Website <span class="app-text-muted fw-normal">(optional)</span></label>
            <input type="url" name="company_website" id="company_website"
                class="form-control @error('company_website') is-invalid @enderror"
                value="{{ old('company_website', $job->company_website ?? '') }}" placeholder="https://example.com">
            @error('company_website')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-2">
            <label for="salary_currency" class="form-label fw-semibold">Currency</label>
            <input type="text" name="salary_currency" id="salary_currency"
                class="form-control @error('salary_currency') is-invalid @enderror"
                value="{{ old('salary_currency', $job->salary_currency ?? 'NPR') }}" maxlength="10">
            @error('salary_currency')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-5">
            <label for="salary_min" class="form-label fw-semibold">Salary Min <span class="app-text-muted fw-normal">(optional)</span></label>
            <input type="number" name="salary_min" id="salary_min"
                class="form-control @error('salary_min') is-invalid @enderror"
                value="{{ old('salary_min', $job->salary_min ?? '') }}" min="0" step="0.01" placeholder="0">
            @error('salary_min')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-5">
            <label for="salary_max" class="form-label fw-semibold">Salary Max <span class="app-text-muted fw-normal">(optional)</span></label>
            <input type="number" name="salary_max" id="salary_max"
                class="form-control @error('salary_max') is-invalid @enderror"
                value="{{ old('salary_max', $job->salary_max ?? '') }}" min="0" step="0.01" placeholder="0">
            @error('salary_max')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        @auth
        @if(auth()->user()->isAdmin())
            <div class="col-12">
                <div class="form-check">
                    <input type="checkbox" name="is_featured" id="is_featured" value="1"
                        class="form-check-input @error('is_featured') is-invalid @enderror"
                        {{ old('is_featured', $job->is_featured ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label fw-semibold" for="is_featured">
                        <i class="bi bi-star-fill text-warning me-1"></i> Featured Job
                    </label>
                    @error('is_featured')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        @endif
        @endauth

        <div class="col-12 pt-2 border-top">
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary rounded-pill px-5">
                    <i class="bi bi-check-lg me-2"></i>{{ $buttonText }}
                </button>
                <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary rounded-pill px-4">Cancel</a>
            </div>
        </div>
    </div>
</form>
