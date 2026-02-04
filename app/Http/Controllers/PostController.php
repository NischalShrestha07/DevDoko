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
use Illuminate\Support\Facades\Log;

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
            Log::info('Post creation request received', ['user_id' => Auth::id(), 'type' => $request->type]);

            // DEBUG: Log what's in the request
            Log::debug('Request data', [
                'type' => $request->type,
                'has_video' => $request->hasFile('video'),
                'video_size' => $request->file('video') ? $request->file('video')->getSize() : null,
                'all_data' => $request->except(['_token', 'video'])
            ]);
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
                Log::warning('Post validation failed', ['errors' => $validator->errors()->all()]);

                // DEBUG: Check what we're returning
                $response = back()->withErrors($validator)->withInput();
                Log::debug('Returning response type: ' . get_class($response));

                return $response;
            }

            $validated = $validator->validated();
            Log::info('Post validation passed', ['type' => $validated['type']]);

            // Validate content based on type
            if (!$this->validatePostContent($request, $validated['type'])) {
                Log::warning('Post content validation failed');
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

            Log::info('Creating post', $postData);

            $post = Post::create($postData);
            Log::info('Post created successfully', ['post_id' => $post->id]);

            // Handle tags
            $this->handleTags($request, $post);
            Log::info('Tags handled for post', ['post_id' => $post->id]);

            // Create notification for followers
            try {
                $this->notifyFollowers($post);
                Log::info('Notifications sent for post', ['post_id' => $post->id]);
            } catch (\Exception $e) {
                Log::error('Failed to send notifications', ['error' => $e->getMessage()]);
                // Don't fail the post creation if notifications fail
            }

            // Create activity log if package exists
            try {
                if (function_exists('activity')) {
                    activity()
                        ->causedBy(Auth::user())
                        ->performedOn($post)
                        ->log('created_post');
                    Log::info('Activity log created');
                }
            } catch (\Exception $e) {
                Log::warning('Activity log failed', ['error' => $e->getMessage()]);
            }

            return redirect()->route('posts.show', $post)
                ->with('success', 'Post created successfully!');
        } catch (\Exception $e) {
            Log::error('Post creation failed: ' . $e->getMessage(), [
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
                $query->with(['user.profile', 'replies' => function ($q) {
                    $q->with('user.profile')->orderBy('created_at', 'desc');
                }])
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
        try {
            if (function_exists('activity')) {
                activity()
                    ->causedBy(Auth::user())
                    ->performedOn($post)
                    ->log('updated_post');
            }
        } catch (\Exception $e) {
            Log::warning('Activity log failed on update', ['error' => $e->getMessage()]);
        }

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
        try {
            if (function_exists('activity')) {
                activity()
                    ->causedBy(Auth::user())
                    ->performedOn($post)
                    ->log('deleted_post');
            }
        } catch (\Exception $e) {
            Log::warning('Activity log failed on delete', ['error' => $e->getMessage()]);
        }

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
            try {
                // You need to create this notification class
                // $post->user->notify(new \App\Notifications\PostShared($sharedPost, Auth::user()));
            } catch (\Exception $e) {
                Log::error('Failed to send share notification', ['error' => $e->getMessage()]);
            }
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
            try {
                $path = $request->file('image')->store('posts/images', 'public');
                Log::info('Image uploaded successfully', ['path' => $path]);
                return $path;
            } catch (\Exception $e) {
                Log::error('Image upload failed', ['error' => $e->getMessage()]);
                return null;
            }
        }
        return null;
    }

    private function handleVideoUpload(Request $request): ?string
    {
        if ($request->hasFile('video')) {
            try {
                $path = $request->file('video')->store('posts/videos', 'public');
                Log::info('Video uploaded successfully', ['path' => $path]);
                return $path;
            } catch (\Exception $e) {
                Log::error('Video upload failed', ['error' => $e->getMessage()]);
                return null;
            }
        }
        return null;
    }

    private function handleTags(Request $request, Post $post): void
    {
        try {
            if ($request->filled('tags')) {
                $tagNames = array_filter(array_map('trim', explode(',', $request->tags)));
                Log::info('Processing tags', ['tags' => $tagNames]);

                foreach ($tagNames as $tagName) {
                    if (!empty($tagName) && strlen($tagName) <= 50) {
                        $slug = Str::slug($tagName);
                        $tag = Tag::firstOrCreate(
                            ['slug' => $slug],
                            ['name' => $tagName, 'slug' => $slug]
                        );
                        Log::info('Attaching tag', ['tag_id' => $tag->id, 'tag_name' => $tag->name]);
                        $post->tags()->attach($tag->id);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Tag handling failed', ['error' => $e->getMessage(), 'post_id' => $post->id]);
            // Don't throw exception - tags are optional
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
        try {
            if (!in_array($post->visibility, ['public', 'followers'])) {
                return;
            }

            $followers = Auth::user()->followers;
            Log::info('Notifying followers', ['count' => $followers->count()]);

            foreach ($followers as $follower) {
                try {
                    // Create notification record
                    \App\Models\Notification::create([
                        'user_id' => $follower->id,
                        'type' => 'new_post',
                        'data' => json_encode([
                            'post_id' => $post->id,
                            'post_title' => $post->title ?? 'New Post',
                            'user_name' => Auth::user()->name,
                            'user_id' => Auth::id(),
                            'message' => Auth::user()->name . ' created a new post'
                        ]),
                        'read_at' => null
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to create notification for follower', [
                        'follower_id' => $follower->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Notify followers failed', ['error' => $e->getMessage()]);
            // Don't throw - notifications shouldn't break post creation
        }
    }
}
