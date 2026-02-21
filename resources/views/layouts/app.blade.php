<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DevDoko')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Highlight.js -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/github-dark.min.css">
    <link rel="icon" href="{{ asset('assets/devdokoIcon.png') }}">
</head>

<body class="bg-light">
    <!-- Desktop Sidebar - Using Bootstrap classes -->
    <div class="d-none d-md-flex flex-column bg-white border-end vh-100 position-fixed"
        style="width: 260px; top: 0; left: 0;">
        <!-- Logo -->
        <div class="p-3 border-bottom">
            <div class="d-flex align-items-center">
                <img src="{{ asset('/assets/devdoko.png') }}" alt="DevDoko" class="rounded-circle border border-dark"
                    style="width: 50px; height: 50px; object-fit: cover;">
                <span class="ms-2 fw-bold fs-4">DevDoko</span>
            </div>
        </div>

        <!-- Scrollable Menu -->
        <div class="flex-grow-1 overflow-auto py-2">
            <!-- Main Navigation -->
            <div class="px-2 mb-3">
                <div class="small text-secondary text-uppercase fw-semibold px-3 mb-2">Main</div>
                <a href="{{ route('home') }}"
                    class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none rounded-3 {{ request()->routeIs('home') ? 'bg-light fw-semibold' : '' }} hover-bg-light">
                    <i class="bi bi-house-door{{ request()->routeIs('home') ? '-fill' : '' }} fs-5 me-3"></i>
                    <span>Home</span>
                </a>
                <a href="{{ route('search') }}"
                    class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none rounded-3 {{ request()->routeIs('search') ? 'bg-light fw-semibold' : '' }} hover-bg-light">
                    <i class="bi bi-search fs-5 me-3"></i>
                    <span>Search</span>
                </a>
                <a href="{{ route('explore') }}"
                    class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none rounded-3 {{ request()->routeIs('explore') ? 'bg-light fw-semibold' : '' }} hover-bg-light">
                    <i class="bi bi-compass fs-5 me-3"></i>
                    <span>Explore</span>
                </a>
                <a href="{{ route('messages.index') }}"
                    class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none rounded-3 {{ request()->routeIs('messages.*') ? 'bg-light fw-semibold' : '' }} hover-bg-light">
                    <i class="bi bi-chat{{ request()->routeIs('messages.*') ? '-fill' : '' }} fs-5 me-3"></i>
                    <span>Messages</span>
                </a>
                <a href="{{ route('notifications.index') }}"
                    class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none rounded-3 {{ request()->routeIs('notifications.*') ? 'bg-light fw-semibold' : '' }} hover-bg-light">
                    <i class="bi bi-heart{{ request()->routeIs('notifications.*') ? '-fill' : '' }} fs-5 me-3"></i>
                    <span>Notifications</span>
                    @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                    @if($unreadCount > 0)
                    <span class="badge bg-danger rounded-pill ms-auto">{{ $unreadCount }}</span>
                    @endif
                </a>
                <a href="{{ route('posts.create') }}"
                    class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none rounded-3 {{ request()->routeIs('posts.create') ? 'bg-light fw-semibold' : '' }} hover-bg-light">
                    <i class="bi bi-plus-square{{ request()->routeIs('posts.create') ? '-fill' : '' }} fs-5 me-3"></i>
                    <span>Create</span>
                </a>
                <a href="{{ route('profile.show', auth()->user()->profile->username) }}"
                    class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none rounded-3 {{ request()->routeIs('profile.show') ? 'bg-light fw-semibold' : '' }} hover-bg-light">
                    @if(auth()->user()->profile->avatar)
                    <img src="{{ auth()->user()->profile->avatar_url }}" class="rounded-circle me-3"
                        style="width: 20px; height: 20px; object-fit: cover;">
                    @else
                    <i class="bi bi-person-circle fs-5 me-3"></i>
                    @endif
                    <span>Profile</span>
                </a>
            </div>

            <!-- Groups Dropdown -->
            <div class="px-2 mb-3">
                <div class="small text-secondary text-uppercase fw-semibold px-3 mb-2">Community</div>
                <div class="dropdown">
                    <a href="#"
                        class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none rounded-3 dropdown-toggle"
                        data-bs-toggle="dropdown">
                        <i class="bi bi-people-fill fs-5 me-3"></i>
                        <span class="flex-grow-1">Groups</span>
                    </a>
                    <ul class="dropdown-menu w-100 mt-1 shadow-sm border-0">
                        <li><a class="dropdown-item py-2 {{ request()->routeIs('groups.index') ? 'active bg-light' : '' }}"
                                href="{{ route('groups.index') }}"><i class="bi bi-compass me-2"></i> Discover
                                Groups</a></li>
                        <li><a class="dropdown-item py-2 {{ request()->routeIs('groups.my-groups') ? 'active bg-light' : '' }}"
                                href="{{ route('groups.my-groups') }}"><i class="bi bi-bookmark-check me-2"></i> My
                                Groups</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item py-2" href="{{ route('groups.create') }}"><i
                                    class="bi bi-plus-circle me-2 text-primary"></i> Create Group</a></li>
                    </ul>
                </div>
            </div>

            <!-- Marketplace Section -->
            <div class="px-2 mb-3">
                <div class="small text-secondary text-uppercase fw-semibold px-3 mb-2">Marketplace</div>

                <!-- Main Marketplace Links -->
                <a href="{{ route('marketplace.index') }}"
                    class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none rounded-3 {{ request()->routeIs('marketplace.index') ? 'bg-light fw-semibold' : '' }} hover-bg-light">
                    <i class="bi bi-shop fs-5 me-3"></i>
                    <span>Browse</span>
                </a>

                <a href="{{ route('marketplace.my-listings') }}"
                    class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none rounded-3 {{ request()->routeIs('marketplace.my-listings') ? 'bg-light fw-semibold' : '' }} hover-bg-light">
                    <i class="bi bi-bag fs-5 me-3"></i>
                    <span class="flex-grow-1">My Listings</span>
                    @php $activeListingsCount = auth()->user()->marketplaceListings()->where('status',
                    'active')->count(); @endphp
                    @if($activeListingsCount > 0)
                    <span class="badge bg-success rounded-pill">{{ $activeListingsCount }}</span>
                    @endif
                </a>

                <!-- Interests Dropdown -->
                <div class="dropdown mt-1">
                    <a href="#"
                        class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none rounded-3 dropdown-toggle"
                        data-bs-toggle="dropdown">
                        <i class="bi bi-chat-heart fs-5 me-3"></i>
                        <span class="flex-grow-1">Interests</span>
                    </a>
                    <ul class="dropdown-menu w-100 mt-1 shadow-sm border-0">
                        <li>
                            <a class="dropdown-item py-2 d-flex justify-content-between align-items-center"
                                href="{{ route('marketplace.interests.received') }}">
                                <span><i class="bi bi-inbox me-2"></i> Received</span>
                                @php
                                $pendingReceived = auth()->user()->marketplaceListings()
                                ->withCount(['interests' => function($q) { $q->where('status', 'pending'); }])
                                ->get()->sum('interests_count');
                                @endphp
                                @if($pendingReceived > 0)
                                <span class="badge bg-warning rounded-pill">{{ $pendingReceived }}</span>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 d-flex justify-content-between align-items-center"
                                href="{{ route('marketplace.interests.sent') }}">
                                <span><i class="bi bi-send me-2"></i> Sent</span>
                                @php $pendingSent = auth()->user()->marketplaceInterests()->where('status',
                                'pending')->count(); @endphp
                                @if($pendingSent > 0)
                                <span class="badge bg-info rounded-pill">{{ $pendingSent }}</span>
                                @endif
                            </a>
                        </li>
                    </ul>
                </div>

                <a href="{{ route('marketplace.saved') }}"
                    class="d-flex align-items-center px-3 py-2 text-dark text-decoration-none rounded-3 {{ request()->routeIs('marketplace.saved') ? 'bg-light fw-semibold' : '' }} hover-bg-light mt-1">
                    <i class="bi bi-bookmark fs-5 me-3"></i>
                    <span class="flex-grow-1">Saved</span>
                    @php $savedCount = auth()->user()->savedMarketplaceListings()->count(); @endphp
                    @if($savedCount > 0)
                    <span class="badge bg-primary rounded-pill">{{ $savedCount }}</span>
                    @endif
                </a>

                <a href="{{ route('marketplace.create') }}"
                    class="d-flex align-items-center px-3 py-2 text-primary text-decoration-none rounded-3 hover-bg-light mt-2">
                    <i class="bi bi-plus-circle fs-5 me-3"></i>
                    <span>Sell Something</span>
                </a>
            </div>
        </div>

        <!-- Logout Button -->
        <div class="p-3 border-top">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="d-flex align-items-center w-100 px-3 py-2 text-dark bg-transparent border-0 rounded-3 hover-bg-light">
                    <i class="bi bi-box-arrow-right fs-5 me-3"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content Area -->
    <main class="main-content" style="margin-left: 260px;">
        <!-- Flash Messages -->
        @if(session('success'))
        <div class="container pt-3">
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="container pt-3">
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                    <span>{{ session('error') }}</span>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
        @endif

        @yield('content')
    </main>

    <!-- Mobile Bottom Navigation -->
    <nav class="d-md-none fixed-bottom bg-white border-top py-2 px-3">
        <div class="d-flex justify-content-around align-items-center">
            <a href="{{ route('home') }}" class="text-dark text-decoration-none text-center">
                <i class="bi bi-house-door{{ request()->routeIs('home') ? '-fill' : '' }} fs-5"></i>
                <small class="d-block" style="font-size: 10px;">Home</small>
            </a>
            <a href="{{ route('search') }}" class="text-dark text-decoration-none text-center">
                <i class="bi bi-search fs-5"></i>
                <small class="d-block" style="font-size: 10px;">Search</small>
            </a>
            <a href="{{ route('marketplace.index') }}" class="text-primary text-decoration-none text-center">
                <i class="bi bi-shop fs-5"></i>
                <small class="d-block" style="font-size: 10px;">Shop</small>
            </a>
            <a href="{{ route('notifications.index') }}"
                class="text-dark text-decoration-none text-center position-relative">
                <i class="bi bi-heart{{ request()->routeIs('notifications.*') ? '-fill' : '' }} fs-5"></i>
                @if($unreadCount > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                    style="font-size: 8px;">{{ $unreadCount }}</span>
                @endif
                <small class="d-block" style="font-size: 10px;">Activity</small>
            </a>
            <a href="{{ route('profile.show', auth()->user()->profile->username) }}"
                class="text-dark text-decoration-none text-center">
                @if(auth()->user()->profile->avatar)
                <img src="{{ auth()->user()->profile->avatar_url }}" class="rounded-circle"
                    style="width: 20px; height: 20px; object-fit: cover;">
                @else
                <i class="bi bi-person-circle fs-5"></i>
                @endif
                <small class="d-block" style="font-size: 10px;">Profile</small>
            </a>
        </div>
    </nav>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof hljs !== 'undefined') {
                document.querySelectorAll('pre code').forEach((block) => {
                    hljs.highlightElement(block);
                });
            }
        });
    </script>

    <!-- Marketplace Interactions -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Save/Unsave Listing
            document.querySelectorAll('.save-listing-btn').forEach(btn => {
                btn.addEventListener('click', async function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const listingId = this.dataset.listingId;
                    const icon = this.querySelector('i');
                    const textSpan = this.querySelector('.save-text');

                    if (!listingId) return;

                    try {
                        const response = await fetch(`/marketplace/save/${listingId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            if (data.saved) {
                                btn.classList.remove('btn-outline-primary');
                                btn.classList.add('btn-primary');
                                icon.classList.remove('bi-bookmark');
                                icon.classList.add('bi-bookmark-fill');
                                textSpan.textContent = 'Saved';
                                btn.dataset.saved = 'true';
                            } else {
                                btn.classList.remove('btn-primary');
                                btn.classList.add('btn-outline-primary');
                                icon.classList.remove('bi-bookmark-fill');
                                icon.classList.add('bi-bookmark');
                                textSpan.textContent = btn.classList.contains('w-100') ? 'Save Listing' : 'Save';
                                btn.dataset.saved = 'false';
                            }
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    }
                });
            });

            // Express Interest Modal
            window.showInterestModal = function(listingId, listingTitle) {
                const modal = document.getElementById('interestModal');
                if (!modal) return;

                modal.dataset.listingId = listingId;
                const modalTitle = modal.querySelector('.modal-title');
                if (modalTitle) modalTitle.textContent = `Express Interest in "${listingTitle}"`;

                new bootstrap.Modal(modal).show();
            };

            // Submit Interest
            const submitInterestBtn = document.getElementById('submitInterestBtn');
            if (submitInterestBtn) {
                submitInterestBtn.addEventListener('click', async function() {
                    const modal = document.getElementById('interestModal');
                    const listingId = modal.dataset.listingId;

                    if (!listingId) return;

                    const message = modal.querySelector('textarea[name="message"]')?.value || '';
                    const offeredPrice = modal.querySelector('input[name="offered_price"]')?.value || '';

                    try {
                        const response = await fetch(`/marketplace/interest/${listingId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                message: message,
                                offered_price: offeredPrice ? parseFloat(offeredPrice) : null
                            })
                        });

                        const data = await response.json();

                        if (response.ok && data.success) {
                            bootstrap.Modal.getInstance(modal).hide();
                            modal.querySelector('textarea[name="message"]').value = '';
                            modal.querySelector('input[name="offered_price"]').value = '';
                            alert('Interest expressed successfully!');
                        } else {
                            alert(data.error || 'Failed to express interest');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    }
                });
            }

            // Delete Listing
            window.deleteListing = function(listingId) {
                if (confirm('Delete this listing?')) {
                    fetch(`/marketplace/${listingId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                        }
                    }).then(response => {
                        if (response.ok) window.location.href = '{{ route("marketplace.index") }}';
                    });
                }
            };
        });
    </script>
</body>

</html>