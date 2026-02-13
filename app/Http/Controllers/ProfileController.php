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
            'user',
            'techTags'
        ])
            ->where('username', $username)
            ->firstOrFail();

        // Get user's posts with proper eager loading
        $posts = $profile->user->posts()
            ->with(['likes', 'comments', 'tags', 'media', 'user.profile'])
            ->withCount(['likes', 'comments', 'saves'])
            ->latest()
            ->paginate(12);

        // Get saved posts if authenticated and viewing own profile
        $savedPosts = collect();
        if (Auth::check() && Auth::id() === $profile->user_id) {
            $savedPosts = Auth::user()->savedPosts()
                ->with(['likes', 'comments', 'tags', 'media', 'user.profile'])
                ->withCount(['likes', 'comments', 'saves'])
                ->latest()
                ->paginate(12, ['*'], 'saved_page');
        }

        // Get tagged posts (if you have a tagging system)
        $taggedPosts = collect();
        if (Auth::check() && Auth::id() === $profile->user_id) {
            // Implement your tagged posts logic here
            // $taggedPosts = $profile->user->taggedPosts()->paginate(12, ['*'], 'tagged_page');
        }

        $isFollowing = false;
        if (Auth::check()) {
            $isFollowing = Auth::user()->isFollowing($profile->user);
        }

        // Get followers/following counts
        $followersCount = $profile->user->followers()->count();
        $followingCount = $profile->user->following()->count();
        $postsCount = $profile->user->posts()->count();

        return view('profiles.show', compact(
            'profile',
            'posts',
            'savedPosts',
            'taggedPosts',
            'isFollowing',
            'followersCount',
            'followingCount',
            'postsCount'
        ));
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

    public function followers(User $user)
    {
        $followers = $user->followers()
            ->with(['profile', 'posts' => function ($query) {
                $query->latest()->take(3);
            }])
            ->paginate(20);

        if (request()->expectsJson()) {
            return response()->json(['followers' => $followers]);
        }

        return view('follow.followers', compact('user', 'followers'));
    }

    /**
     * Get following list
     */
    public function following(User $user)
    {
        $following = $user->following()
            ->with(['profile', 'posts' => function ($query) {
                $query->latest()->take(3);
            }])
            ->paginate(20);

        if (request()->expectsJson()) {
            return response()->json(['following' => $following]);
        }

        return view('profiles.following', compact('user', 'following'));
    }

    /**
     * Get user's saved posts
     */
    public function saved(User $user)
    {
        $savedPosts = $user->saves()
            ->with('post.user.profile')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('profiles.saved', compact('user', 'savedPosts'));
    }
}
