{{-- resources/views/marketplace/partials/my-listings-table.blade.php --}}
@if($listings->count() > 0)
<div class="table-responsive">
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Category</th>
                <th>Status</th>
                <th>Views</th>
                <th>Interests</th>
                <th>Posted</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($listings as $listing)
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        @if($listing->images->count() > 0)
                        <img src="{{ Storage::url($listing->images->first()->image_path) }}" class="rounded me-2"
                            style="width: 40px; height: 40px; object-fit: cover;">
                        @else
                        <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;">
                            <i class="bi bi-image text-muted"></i>
                        </div>
                        @endif
                        <div>
                            <a href="{{ route('marketplace.show', $listing->slug) }}"
                                class="fw-semibold text-dark text-decoration-none">
                                {{ Str::limit($listing->title, 30) }}
                            </a>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="fw-semibold">{{ $listing->formatted_price }}</span>
                </td>
                <td>
                    <span class="badge bg-primary">{{ $listing->category ?? 'Uncategorized' }}</span>
                </td>
                <td>
                    @php
                    $badgeClass = match($listing->status) {
                    'active' => 'bg-success',
                    'sold' => 'bg-secondary',
                    'reserved' => 'bg-warning',
                    'expired' => 'bg-danger',
                    default => 'bg-secondary'
                    };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ ucfirst($listing->status) }}</span>
                </td>
                <td>{{ $listing->views_count }}</td>
                <td>{{ $listing->interests->count() }}</td>
                <td>
                    <small class="text-muted" title="{{ $listing->created_at->format('M d, Y') }}">
                        {{ $listing->created_at->diffForHumans() }}
                    </small>
                </td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('marketplace.show', $listing->slug) }}" class="btn btn-outline-primary"
                            title="View">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('marketplace.edit', $listing) }}" class="btn btn-outline-secondary"
                            title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <button type="button" class="btn btn-outline-danger" title="Delete"
                            onclick="deleteListing({{ $listing->id }})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    function deleteListing(id) {
    if (confirm('Are you sure you want to delete this listing?')) {
        fetch(`/marketplace/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        }).then(response => {
            if (response.ok) {
                window.location.reload();
            } else {
                alert('Failed to delete listing');
            }
        });
    }
}
</script>
@else
<div class="text-center py-5">
    <i class="bi bi-bag-x display-1 text-muted"></i>
    <h5 class="mt-3">No listings found</h5>
    <p class="text-muted">Start by creating your first listing</p>
    <a href="{{ route('marketplace.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i> Create Listing
    </a>
</div>
@endif