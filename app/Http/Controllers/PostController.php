<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\Media;
use App\Models\CodeSnippet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show']);
    }

    // Show create post form
    public function create(Request $request)
    {
        $type = $request->query('type', 'text');
        return view('posts.create', compact('type'));
    }

    // Store new post
    public function store(Request $request)
    {
        // Validate based on post type
        $validationRules = [
            'type' => 'required|in:text,image,video,code',
            'caption' => 'required|string|max:2000',
            'visibility' => 'required|in:public,followers',
            'tags' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:100',
        ];

        // Additional validation for specific types
        if ($request->type === 'code') {
            $validationRules['language'] = 'required|string|max:50';
            $validationRules['code'] = 'required|string|max:10000';
        } elseif (in_array($request->type, ['image', 'video'])) {
            $validationRules['media'] = 'required|array|min:1';
            $validationRules['media.*'] = $request->type === 'image'
                ? 'image|mimes:jpg,jpeg,png,gif,webp|max:5120'
                : 'mimes:mp4,mov,avi,wmv|max:20480';
        }

        $validated = $request->validate($validationRules);

        // Create post
        $post = Post::create([
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'caption' => $validated['caption'],
            'visibility' => $validated['visibility'],
            'location' => $validated['location'] ?? null,
        ]);

        // Handle tags
        if (!empty($validated['tags'])) {
            $tags = array_map('trim', explode(',', $validated['tags']));
            $tagIds = [];

            foreach ($tags as $tagName) {
                if (!empty($tagName)) {
                    $tag = Tag::firstOrCreate([
                        'name' => Str::lower($tagName)
                    ]);
                    $tagIds[] = $tag->id;
                }
            }

            if (!empty($tagIds)) {
                $post->tags()->sync(array_unique($tagIds));
            }
        }

        // Handle media upload for images/videos
        if (in_array($validated['type'], ['image', 'video']) && $request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('posts/' . $validated['type'], 'public');

                Media::create([
                    'post_id' => $post->id,
                    'file_path' => $path,
                    'media_type' => $validated['type'],
                ]);
            }
        }

        // Handle code snippet
        if ($validated['type'] === 'code') {
            CodeSnippet::create([
                'post_id' => $post->id,
                'language' => $validated['language'],
                'code' => $validated['code'],
            ]);
        }

        return redirect()->route('posts.show', $post)
            ->with('success', 'Post created successfully!');
    }

    // Show single post
    public function show(Post $post)
    {
        // Check if user can view the post
        if ($post->visibility === 'followers') {
            if (!Auth::check()) {
                abort(403, 'This post is only visible to followers');
            }

            if (!Auth::user()->isFollowing($post->user) && Auth::id() !== $post->user_id) {
                abort(403, 'This post is only visible to followers');
            }
        }

        $post->load([
            'user.profile',
            'tags',
            'likes.user.profile',
            'comments' => function ($query) {
                $query->with('user.profile')->latest();
            },
            'media',
            'codeSnippet'
        ]);

        // Increment view count (you can add a views column to posts table)
        // $post->increment('views');

        return view('posts.show', compact('post'));
    }

    // Delete post
    public function destroy(Post $post)
    {
        // Authorization check
        if (Auth::id() !== $post->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Delete associated media files
        foreach ($post->media as $media) {
            Storage::disk('public')->delete($media->file_path);
            $media->delete();
        }

        // Delete code snippet if exists
        if ($post->codeSnippet) {
            $post->codeSnippet->delete();
        }

        // Delete the post
        $post->delete();

        return redirect()->route('home')
            ->with('success', 'Post deleted successfully!');
    }
}
