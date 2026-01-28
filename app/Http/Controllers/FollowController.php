<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Follow a user
     */
    public function follow(User $user)
    {
        if (Auth::id() === $user->id) {
            return redirect()->back()->with('error', 'You cannot follow yourself');
        }

        if (!Auth::user()->isFollowing($user)) {
            Auth::user()->following()->attach($user->id);

            // Add reputation to the user being followed
            $user->profile->incrementReputation(5, 'follow_received');

            return redirect()->back()->with('success', "You are now following {$user->name}");
        }

        return redirect()->back()->with('info', 'You are already following this user');
    }

    /**
     * Unfollow a user
     */
    public function unfollow(User $user)
    {
        if (Auth::user()->isFollowing($user)) {
            Auth::user()->following()->detach($user->id);

            // Remove reputation from the user being unfollowed
            $user->profile->decrementReputation(5, 'follow_lost');

            return redirect()->back()->with('success', "You have unfollowed {$user->name}");
        }

        return redirect()->back()->with('info', 'You are not following this user');
    }

    /**
     * Show user's followers
     */
    public function followers(User $user)
    {
        $followers = $user->followers()
            ->with('profile')
            ->paginate(20);

        return view('follow.followers', compact('user', 'followers'));
    }

    /**
     * Show who user is following
     */
    public function following(User $user)
    {
        $following = $user->following()
            ->with('profile')
            ->paginate(20);

        return view('follow.following', compact('user', 'following'));
    }
}
