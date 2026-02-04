<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['user.profile', 'tags', 'likes', 'comments.user.profile'])
            ->visibleTo(Auth::user())
            ->orderBy('created_at', 'desc');

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filter by tag
        if ($request->has('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        $posts = $query->paginate(20);

        return view('posts.index', compact('posts'));
    }

    public function create(Request $request)
    {
        $type = $request->query('type', 'text');
        $tags = Tag::orderBy('name')->get();

        return view('posts.create', compact('type', 'tags'));
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'nullable|string|max:200',
                'content' => 'nullable|string|max:20000',
                'type' => 'required|in:text,code,image,video,link,question,project,article,status',
                'code_snippet' => 'nullable|string|max:20000',
                'code_language' => 'nullable|string|max:50',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:20480',
                'video' => 'nullable|mimes:mp4,avi,mov,wmv|max:51200',
                'link_url' => 'nullable|url|max:500',
                'link_title' => 'nullable|string|max:200',
                'link_description' => 'nullable|string|max:500',
                'link_image' => 'nullable|url|max:500',
                'tags' => 'nullable|string',
                'visibility' => 'required|in:public,followers,private'
            ]);


            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $validated = $validator->validated();

            // Validate content based on type
            if (!$this->validatePostContent($request, $validated['type'])) {
                return back()->withErrors(['content' => 'Please provide content for your post type.'])->withInput();
            }

            // Handle file uploads
            $imagePath = $this->handleImageUpload($request);
            $videoPath = $this->handleVideoUpload($request);

            // Create post
            $postData = [
                'user_id' => Auth::id(),
                'title' => $validated['title'] ?? null,
                'content' => $validated['content'] ?? null,
                'type' => $validated['type'],
                'code_snippet' => $validated['code_snippet'] ?? null,
                'code_language' => $validated['code_language'] ?? null,
                'image_path' => $imagePath,
                'video_path' => $videoPath,
                'link_url' => $validated['link_url'] ?? null,
                'link_title' => $validated['link_title'] ?? null,
                'link_description' => $validated['link_description'] ?? null,
                'link_image' => $validated['link_image'] ?? null,
                'visibility' => $validated['visibility'],
                'reading_time' => $this->calculateReadingTime($validated['content'] ?? '')
            ];


            $post = Post::create($postData);
            // return $post;

            // Handle tags
            $this->handleTags($request, $post);

            // Create notification for followers
            $this->notifyFollowers($post);

            // Create activity log
            activity()
                ->causedBy(Auth::user())
                ->performedOn($post)
                ->log('created_post');

            return redirect()->route('posts.show', $post)
                ->with('success', 'Post created successfully!');
        } catch (\Exception $e) {
            \Log::error('Post creation failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'request' => $request->except(['image', 'video'])
            ]);

            return back()->withErrors(['error' => 'Failed to create post. Please try again.'])->withInput();
        }
    }

    public function show(Post $post)
    {
        // Check if user can view this post
        if (!$post->canView(Auth::user())) {
            abort(403, 'You do not have permission to view this post.');
        }

        $post->load([
            'user.profile',
            'comments' => function ($query) {
                $query->with(['user.profile', 'replies.user.profile'])
                    ->whereNull('parent_id')
                    ->orderBy('created_at', 'desc')
                    ->take(10);
            },
            'likes.user.profile',
            'tags',
            'media'
        ]);

        $post->incrementViews();

        $relatedPosts = Post::whereHas('tags', function ($query) use ($post) {
            $query->whereIn('tags.id', $post->tags->pluck('id'));
        })
            ->where('id', '!=', $post->id)
            ->visibleTo(Auth::user())
            ->with('user.profile')
            ->limit(4)
            ->get();

        $isBookmarked = $post->is_saved;
        $isLiked = $post->is_liked;

        return view('posts.show', compact('post', 'relatedPosts', 'isBookmarked', 'isLiked'));
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        $tags = Tag::orderBy('name')->get();
        $selectedTags = $post->tags->pluck('id')->toArray();

        return view('posts.edit', compact('post', 'tags', 'selectedTags'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:200',
            'content' => 'nullable|string|max:20000',
            'code_snippet' => 'nullable|string|max:20000',
            'code_language' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:20480',
            'video' => 'nullable|mimes:mp4,avi,mov,wmv|max:51200',
            'link_url' => 'nullable|url|max:500',
            'link_title' => 'nullable|string|max:200',
            'link_description' => 'nullable|string|max:500',
            'link_image' => 'nullable|url|max:500',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'remove_image' => 'boolean',
            'remove_video' => 'boolean',
            'visibility' => 'required|in:public,followers,private'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        // Handle image removal
        if ($request->has('remove_image') && $post->image_path) {
            Storage::disk('public')->delete($post->image_path);
            $validated['image_path'] = null;
        }

        // Handle video removal
        if ($request->has('remove_video') && $post->video_path) {
            Storage::disk('public')->delete($post->video_path);
            $validated['video_path'] = null;
        }

        // Handle new image upload
        if ($request->hasFile('image')) {
            if ($post->image_path) {
                Storage::disk('public')->delete($post->image_path);
            }
            $path = $request->file('image')->store('posts/images', 'public');
            $validated['image_path'] = $path;
        }

        // Handle new video upload
        if ($request->hasFile('video')) {
            if ($post->video_path) {
                Storage::disk('public')->delete($post->video_path);
            }
            $path = $request->file('video')->store('posts/videos', 'public');
            $validated['video_path'] = $path;
        }

        // Update reading time
        $validated['reading_time'] = $this->calculateReadingTime($validated['content'] ?? '');

        // Update post
        $post->update(array_filter($validated));

        // Sync tags
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        } else {
            $post->tags()->detach();
        }

        // Handle new tags from string input
        if ($request->filled('new_tags')) {
            $newTags = array_filter(array_map('trim', explode(',', $request->new_tags)));
            foreach ($newTags as $tagName) {
                if (!empty($tagName) && strlen($tagName) <= 50) {
                    $slug = Str::slug($tagName);
                    $tag = Tag::firstOrCreate(
                        ['slug' => $slug],
                        ['name' => $tagName, 'slug' => $slug]
                    );
                    $post->tags()->syncWithoutDetaching($tag->id);
                }
            }
        }

        // Create activity log
        activity()
            ->causedBy(Auth::user())
            ->performedOn($post)
            ->log('updated_post');

        return redirect()->route('posts.show', $post)
            ->with('success', 'Post updated successfully!');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        // Delete associated files
        if ($post->image_path) {
            Storage::disk('public')->delete($post->image_path);
        }
        if ($post->video_path) {
            Storage::disk('public')->delete($post->video_path);
        }

        // Delete associated media
        foreach ($post->media as $media) {
            Storage::disk('public')->delete($media->file_path);
            $media->delete();
        }

        // Create activity log
        activity()
            ->causedBy(Auth::user())
            ->performedOn($post)
            ->log('deleted_post');

        $post->delete();

        return redirect()->route('home')
            ->with('success', 'Post deleted successfully!');
    }

    public function pin(Post $post)
    {
        $this->authorize('update', $post);

        $post->update(['is_pinned' => !$post->is_pinned]);

        return back()->with('success', $post->is_pinned ? 'Post pinned!' : 'Post unpinned!');
    }

    public function share(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        // Create shared post
        $sharedPost = Post::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'type' => 'share',
            'link_url' => route('posts.show', $post),
            'link_title' => $post->title ?? 'Shared Post',
            'link_description' => $post->excerpt,
            'link_image' => $post->image_url,
            'visibility' => 'public'
        ]);

        // Increment share count
        $post->increment('shares_count');

        // Create notification for original post owner
        if ($post->user_id !== Auth::id()) {
            $post->user->notify(new \App\Notifications\PostShared($sharedPost, Auth::user()));
        }

        return redirect()->route('posts.show', $sharedPost)
            ->with('success', 'Post shared successfully!');
    }

    // Private helper methods
    private function validatePostContent(Request $request, string $type): bool
    {
        return match ($type) {
            'text', 'article', 'question', 'project', 'status' => !empty($request->content),
            'code' => !empty($request->code_snippet),
            'image' => $request->hasFile('image') || !empty($request->content),
            'video' => $request->hasFile('video') || !empty($request->content),
            'link' => !empty($request->link_url),
            default => false
        };
    }

    private function handleImageUpload(Request $request): ?string
    {
        if ($request->hasFile('image')) {
            return $request->file('image')->store('posts/images', 'public');
        }
        return null;
    }

    private function handleVideoUpload(Request $request): ?string
    {
        if ($request->hasFile('video')) {
            return $request->file('video')->store('posts/videos', 'public');
        }
        return null;
    }

    private function handleTags(Request $request, Post $post): void
    {
        if ($request->filled('tags')) {
            $tagNames = array_filter(array_map('trim', explode(',', $request->tags)));

            foreach ($tagNames as $tagName) {
                if (!empty($tagName) && strlen($tagName) <= 50) {
                    $slug = Str::slug($tagName);
                    $tag = Tag::firstOrCreate(
                        ['slug' => $slug],
                        ['name' => $tagName, 'slug' => $slug]
                    );
                    $post->tags()->attach($tag->id);
                }
            }
        }
    }

    private function calculateReadingTime(?string $content): int
    {
        if (empty($content)) return 1;

        $wordCount = str_word_count(strip_tags($content));
        return max(1, ceil($wordCount / 200));
    }

    private function notifyFollowers(Post $post): void
    {
        if (!in_array($post->visibility, ['public', 'followers'])) {
            return;
        }

        $followers = Auth::user()->followers()->where('users.notifications_enabled', true)->get();

        foreach ($followers as $follower) {
            $follower->notify(new \App\Notifications\NewPostNotification($post, Auth::user()));
        }
    }
}
