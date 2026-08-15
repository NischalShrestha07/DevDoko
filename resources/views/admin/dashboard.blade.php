@extends('layouts.app')

@section('title', 'Admin Dashboard - DevDoko')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center mb-4">
        <i class="bi bi-shield-check fs-2 me-3 text-primary"></i>
        <div>
            <h4 class="fw-bold mb-0">Admin Dashboard</h4>
            <p class="app-text-muted mb-0">Site overview and management</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-4">
                    <i class="bi bi-people display-4 text-primary mb-3"></i>
                    <h2 class="fw-bold mb-1">{{\App\Models\User::count()}}</h2>
                    <p class="app-text-muted mb-0">Total Users</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-4">
                    <i class="bi bi-file-text display-4 text-success mb-3"></i>
                    <h2 class="fw-bold mb-1">{{\App\Models\Post::count()}}</h2>
                    <p class="app-text-muted mb-0">Total Posts</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-4">
                    <i class="bi bi-people-fill display-4 text-info mb-3"></i>
                    <h2 class="fw-bold mb-1">{{\App\Models\Group::count()}}</h2>
                    <p class="app-text-muted mb-0">Total Groups</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-4">
                    <i class="bi bi-shop display-4 text-warning mb-3"></i>
                    <h2 class="fw-bold mb-1">{{\App\Models\MarketplaceListing::count()}}</h2>
                    <p class="app-text-muted mb-0">Marketplace Listings</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">Quick Links</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <a href="{{ route('developers.index') }}" class="btn btn-outline-primary w-100 py-3">
                        <i class="bi bi-people me-2"></i> Manage Users
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('posts.index') }}" class="btn btn-outline-success w-100 py-3">
                        <i class="bi bi-file-text me-2"></i> Manage Posts
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('groups.index') }}" class="btn btn-outline-info w-100 py-3">
                        <i class="bi bi-people-fill me-2"></i> Manage Groups
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('admin.reports') }}" class="btn btn-outline-danger w-100 py-3">
                        <i class="bi bi-flag me-2"></i> Review Reports
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
