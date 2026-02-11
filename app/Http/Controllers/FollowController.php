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
        return redirect()->back();
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
        return redirect()->back();
    }

    public function followers(User $user)
    {
        $followers = $user->followers()
            ->with('profile')
            ->paginate(20);

        return view('follow.followers', compact('user', 'followers'));
    }

    public function following(User $user)
    {
        $following = $user->following()
            ->with('profile')
            ->paginate(20);

        return view('follow.following', compact('user', 'following'));
    }

    public function toggle(User $user)
    {
        // Prevent user from following themselves
        if (Auth::id() === $user->id) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        $authUser = Auth::user();

        if ($authUser->isFollowing($user)) {
            $authUser->unfollow($user);
            $message = 'Unfollowed successfully.';
        } else {
            $authUser->follow($user);
            $message = 'Followed successfully.';
        }

        return back()->with('success', $message);
    }
}
