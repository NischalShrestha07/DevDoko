{{-- resources/views/search/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Search - DevDoko')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-12">

            {{-- Search Box --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <form method="GET" action="{{ route('search') }}">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-0">
                                <i class="bi bi-search"></i>
                            </span>

                            <input type="text" name="q" class="form-control bg-light border-0"
                                placeholder="Search developers..." value="{{ $query ?? '' }}" autofocus>

                            @if(!empty($query))
                            <a href="{{ route('search') }}" class="btn btn-light border-0">
                                <i class="bi bi-x-lg"></i>
                            </a>
                            @endif

                            <button class="btn btn-primary px-4" type="submit">
                                Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Results --}}
            @if(!empty($query))

            {{-- Result Count --}}
            <div class="mb-3 text-muted">
                @if(isset($users) && method_exists($users, 'total'))
                Found <strong>{{ $users->total() }}</strong> developers for
                "<strong>{{ $query }}</strong>"
                @endif
            </div>

            {{-- Developers List --}}
            @if(isset($users) && $users->count() > 0)

            @foreach($users as $user)
            @include('search.partials.user-card', ['user' => $user])
            @endforeach

            <div class="mt-4">
                {{ $users->appends(request()->query())->links() }}
            </div>

            @else
            <div class="text-center py-5">
                <i class="bi bi-search display-5 text-muted mb-3"></i>
                <h5 class="text-muted">No results found</h5>
                <p class="text-muted small">
                    Try searching with a different keyword.
                </p>
            </div>
            @endif

            @endif

        </div>
    </div>
</div>
@endsection