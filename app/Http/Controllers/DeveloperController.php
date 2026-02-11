<?php
// app/Http/Controllers/DeveloperController.php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DeveloperController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['profile', 'posts']);

        // Filter by search query
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhereHas('profile', function ($profileQuery) use ($search) {
                        $profileQuery->where('username', 'LIKE', "%{$search}%")
                            ->orWhere('bio', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Filter by skill/tag
        if ($request->has('skill')) {
            $query->whereHas('profile', function ($q) use ($request) {
                $q->where('skills', 'LIKE', "%{$request->skill}%");
            });
        }

        // Sort options
        $sort = $request->get('sort', 'recent');
        if ($sort === 'popular') {
            $query->withCount('followers')->orderBy('followers_count', 'desc');
        } elseif ($sort === 'active') {
            $query->orderBy('last_login_at', 'desc');
        } else {
            $query->latest();
        }

        $developers = $query->paginate(12);

        return view('developers.index', compact('developers'));
    }
}
