<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use App\Models\TechTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the specified profile
     */
    public function show($username)
    {
        $profile = Profile::with([
            'user.posts' => function ($query) {
                $query->with(['likes', 'comments', 'tags', 'media'])
                    ->latest();
            },
            'user.followers',
            'user.following',
            'techTags'
        ])
            ->where('username', $username)
            ->firstOrFail();

        $posts = $profile->user->posts()->paginate(12);

        $isFollowing = false;
        if (Auth::check()) {
            $isFollowing = Auth::user()->isFollowing($profile->user);
        }

        return view('profiles.show', compact('profile', 'posts', 'isFollowing'));
    }

    /**
     * Show the form for editing the profile
     */
    public function edit()
    {
        $profile = Auth::user()->profile;
        $techTags = TechTag::all();
        $userTechTags = $profile->techTags->pluck('id')->toArray();

        return view('profiles.edit', compact('profile', 'techTags', 'userTechTags'));
    }

    /**
     * Update the profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        $profile = $user->profile;

        $request->validate([
            'username' => 'required|string|max:30|unique:profiles,username,' . $profile->id,
            'bio' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|max:2048',
            'github_link' => 'nullable|url|max:255',
            'portfolio_link' => 'nullable|url|max:255',
            'tech_tags' => 'array|max:10',
            'tech_tags.*' => 'exists:tech_tags,id',
            'name' => 'nullable|string|max:255',
        ]);

        // Handle avatar upload
        $avatarPath = $profile->avatar;
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($avatarPath && Storage::disk('public')->exists($avatarPath)) {
                Storage::disk('public')->delete($avatarPath);
            }

            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        // Update profile
        $profile->update([
            'username' => $request->username,
            'bio' => $request->bio,
            'avatar' => $avatarPath,
            'github_link' => $request->github_link,
            'portfolio_link' => $request->portfolio_link,

        ]);
        $user->update([
            'name' => $request->name,
        ]);


        // Sync tech tags
        if ($request->has('tech_tags')) {
            $profile->techTags()->sync($request->tech_tags);
        } else {
            $profile->techTags()->detach();
        }

        return redirect()->route('profile.show', $profile->username)
            ->with('success', 'Profile updated successfully!');
    }
}
