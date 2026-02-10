<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function follow(User $user)
    {
        $currentUser = Auth::user();

        // Check if already following
        if ($currentUser->isFollowing($user)) {
            return response()->json([
                'following' => true,
                'message' => 'Already following this user'
            ]);
        }

        // Follow the user
        $currentUser->following()->attach($user->id);

        return response()->json([
            'following' => true,
            'message' => 'Successfully followed user',
            'followers_count' => $user->followers()->count()
        ]);
    }

    public function unfollow(User $user)
    {
        $currentUser = Auth::user();

        // Check if following
        if (!$currentUser->isFollowing($user)) {
            return response()->json([
                'following' => false,
                'message' => 'Not following this user'
            ]);
        }

        // Unfollow the user
        $currentUser->following()->detach($user->id);

        return response()->json([
            'following' => false,
            'message' => 'Successfully unfollowed user',
            'followers_count' => $user->followers()->count()
        ]);
    }

    public function followers(User $user)
    {
        $followers = $user->followers()
            ->with('profile')
            ->paginate(20);

        return view('users.followers', compact('user', 'followers'));
    }

    public function following(User $user)
    {
        $following = $user->following()
            ->with('profile')
            ->paginate(20);

        return view('users.following', compact('user', 'following'));
    }
}
