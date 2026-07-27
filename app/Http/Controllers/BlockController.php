<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BlockController extends Controller
{
    public function index()
    {
        $blockedUsers = Auth::user()
            ->blockedUsers()
            ->with('profile')
            ->paginate(20);

        return view('settings.blocked', compact('blockedUsers'));
    }

    public function store(Request $request, User $user)
    {
        if ($user->id === Auth::id()) {
            return $this->respond($request, false, 'You cannot block yourself.', 422);
        }

        $me = Auth::user();

        DB::transaction(function () use ($me, $user) {
            Block::firstOrCreate([
                'blocker_id' => $me->id,
                'blocked_id' => $user->id,
            ]);

            // Blocking severs the relationship both ways, as on other platforms.
            $me->following()->detach($user->id);
            $me->followers()->detach($user->id);
        });

        return $this->respond($request, true, 'User blocked.');
    }

    public function destroy(Request $request, User $user)
    {
        Block::where('blocker_id', Auth::id())
            ->where('blocked_id', $user->id)
            ->delete();

        return $this->respond($request, false, 'User unblocked.');
    }

    private function respond(Request $request, bool $blocked, string $message, int $status = 200)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => $status === 200,
                'blocked' => $blocked,
                'message' => $message,
            ], $status);
        }

        return $status === 200
            ? back()->with('success', $message)
            : back()->with('error', $message);
    }
}
