<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Models\StoryView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $followingIds = $user->following()->pluck('users.id')->all();
        $userIds = array_merge([$user->id], $followingIds);

        $stories = Story::active()
            ->whereIn('user_id', $userIds)
            ->with('user.profile')
            ->oldest()
            ->get();

        $viewedIds = StoryView::where('user_id', $user->id)
            ->whereIn('story_id', $stories->pluck('id'))
            ->pluck('story_id')
            ->all();

        $groups = $stories->groupBy('user_id')->map(function ($userStories) use ($user, $viewedIds) {
            $owner = $userStories->first()->user;
            $hasUnseen = $userStories->contains(fn ($story) => ! in_array($story->id, $viewedIds));

            return [
                'user_id' => $owner->id,
                'name' => $owner->name,
                'username' => $owner->profile->username ?? $owner->name,
                'avatar_url' => $owner->avatar_url,
                'is_mine' => $owner->id === $user->id,
                'has_unseen' => $hasUnseen,
                'latest_at' => $userStories->last()->created_at->timestamp,
                'stories' => $userStories->map(fn ($story) => [
                    'id' => $story->id,
                    'media_url' => $story->media_url,
                    'media_type' => $story->media_type,
                    'caption' => $story->caption,
                    'time_ago' => $story->created_at->diffForHumans(),
                    'viewed' => in_array($story->id, $viewedIds),
                ])->values(),
            ];
        })->values();

        $mine = $groups->firstWhere('is_mine', true);
        $others = $groups->where('is_mine', false)
            ->sortBy([
                fn ($a, $b) => $b['has_unseen'] <=> $a['has_unseen'],
                fn ($a, $b) => $b['latest_at'] <=> $a['latest_at'],
            ])
            ->values();

        return response()->json([
            'mine' => $mine,
            'others' => $others,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'media' => 'required|file|mimes:jpeg,png,jpg,gif,webp,mp4,mov|max:25600',
            'caption' => 'nullable|string|max:280',
        ]);

        $file = $request->file('media');
        $isVideo = str_starts_with($file->getMimeType(), 'video/');
        $path = $file->store($isVideo ? 'stories/videos' : 'stories/images', 'public');

        $story = Story::create([
            'user_id' => Auth::id(),
            'media_path' => $path,
            'media_type' => $isVideo ? 'video' : 'image',
            'caption' => $data['caption'] ?? null,
            'expires_at' => now()->addHours(24),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'story_id' => $story->id]);
        }

        return back()->with('success', 'Story posted!');
    }

    public function destroy(Story $story)
    {
        if ($story->user_id !== Auth::id()) {
            abort(403, 'You can only delete your own story.');
        }

        Storage::disk('public')->delete($story->media_path);
        $story->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Story deleted.');
    }

    public function markViewed(Story $story)
    {
        if ($story->user_id === Auth::id()) {
            return response()->json(['success' => true]);
        }

        $created = StoryView::firstOrCreate([
            'story_id' => $story->id,
            'user_id' => Auth::id(),
        ]);

        if ($created->wasRecentlyCreated) {
            $story->increment('views_count');
        }

        return response()->json(['success' => true]);
    }

    public function viewers(Story $story)
    {
        if ($story->user_id !== Auth::id()) {
            abort(403, 'You can only view viewers of your own story.');
        }

        $viewers = $story->views()
            ->with('user.profile')
            ->latest()
            ->get()
            ->map(fn ($view) => [
                'name' => $view->user->name,
                'username' => $view->user->profile->username ?? $view->user->name,
                'avatar_url' => $view->user->avatar_url,
                'viewed_at' => $view->created_at->diffForHumans(),
            ]);

        return response()->json(['viewers' => $viewers]);
    }
}
