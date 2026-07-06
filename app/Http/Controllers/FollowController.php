<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function follow(Request $request, User $user)
    {
        $currentUser = Auth::user();

        if ($currentUser->isFollowing($user)) {
            return $request->expectsJson()
                ? response()->json(['following' => true, 'message' => 'Already following this user'])
                : back()->with('info', 'Already following this user');
        }

        $currentUser->following()->attach($user->id);
        $this->notificationService->followNotification($currentUser, $user);

        return $request->expectsJson()
            ? response()->json(['following' => true, 'message' => 'Followed successfully'])
            : back()->with('success', 'Followed successfully');
    }

    public function unfollow(Request $request, User $user)
    {
        $currentUser = Auth::user();

        if (! $currentUser->isFollowing($user)) {
            return $request->expectsJson()
                ? response()->json(['following' => false, 'message' => 'Not following this user'])
                : back()->with('info', 'Not following this user');
        }

        $currentUser->following()->detach($user->id);

        return $request->expectsJson()
            ? response()->json(['following' => false, 'message' => 'Unfollowed successfully'])
            : back()->with('success', 'Unfollowed successfully');
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
        if (Auth::id() === $user->id) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        $authUser = Auth::user();

        if ($authUser->isFollowing($user)) {
            $authUser->following()->detach($user->id);
            $message = 'Unfollowed successfully.';
        } else {
            $authUser->following()->attach($user->id);
            $this->notificationService->followNotification($authUser, $user);
            $message = 'Followed successfully.';
        }

        return back()->with('success', $message);
    }
}
