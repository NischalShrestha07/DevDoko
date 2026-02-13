<?php
// app/Http/Controllers/GroupController.php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupPost;
use App\Models\GroupPostLike;
use App\Models\GroupPostComment;
use App\Models\GroupCommentLike;
use App\Models\GroupResource;
use App\Models\GroupResourceLike;
use App\Models\GroupEvent;
use App\Models\GroupInvitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth')->except(['index', 'show', 'members', 'resources', 'events', 'acceptInvitation']);
    // }

    // ============== GROUP MANAGEMENT ==============

    public function index(Request $request)
    {
        $query = Group::where('privacy', 'public')
            ->with('owner.profile');

        // Filter by category
        if ($request->category) {
            $query->where('category', $request->category);
        }

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Sort
        switch ($request->sort) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'active':
                $query->orderBy('last_active_at', 'desc');
                break;
            case 'popular':
            default:
                $query->orderBy('members_count', 'desc');
                break;
        }

        $groups = $query->paginate(12);

        // Get user's groups if logged in
        $myGroups = collect();
        if (Auth::check()) {
            $myGroups = Group::whereHas('members', function ($q) {
                $q->where('user_id', Auth::id())
                    ->where('status', 'active');
            })->limit(4)->get();
        }

        $categories = [
            'tech-stack' => '💻 Tech Stack',
            'location' => '📍 Location',
            'interest' => '🎯 Interest',
            'project' => '🚀 Project',
            'learning' => '📚 Learning',
        ];

        return view('groups.index', compact('groups', 'myGroups', 'categories'));
    }

    public function create()
    {
        $categories = [
            'tech-stack' => 'Tech Stack - Laravel, React, Python, etc',
            'location' => 'Location - City, Country, Remote',
            'interest' => 'Interest - Open Source, Gaming, AI',
            'project' => 'Project - Startup, Hackathon, Side Project',
            'learning' => 'Learning - Beginners, Interview Prep, System Design',
        ];

        return view('groups.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:groups',
            'description' => 'required|string|max:2000',
            'category' => 'required|in:tech-stack,location,interest,project,learning',
            'tags' => 'nullable|string|max:500',
            'privacy' => 'required|in:public,private,hidden',
            'member_approval' => 'required|in:anyone,admin_approval,invite_only',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $data = $validator->validated();

            // Generate unique slug
            $slug = Str::slug($request->name);
            $count = 1;
            while (Group::where('slug', $slug)->exists()) {
                $slug = Str::slug($request->name) . '-' . $count;
                $count++;
            }
            $data['slug'] = $slug;

            // Handle icon upload
            if ($request->hasFile('icon')) {
                $data['icon'] = $request->file('icon')->store('groups/icons', 'public');
            }

            // Handle cover image upload
            if ($request->hasFile('cover_image')) {
                $data['cover_image'] = $request->file('cover_image')->store('groups/covers', 'public');
            }

            // Process tags
            $data['tags'] = $request->tags ? array_map('trim', explode(',', $request->tags)) : [];

            // Set owner
            $data['owner_id'] = Auth::id();

            $group = Group::create($data);

            DB::commit();

            return redirect()->route('groups.show', $group->slug)
                ->with('success', 'Group created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            // Delete uploaded files if they exist
            if (isset($data['icon'])) {
                Storage::disk('public')->delete($data['icon']);
            }
            if (isset($data['cover_image'])) {
                Storage::disk('public')->delete($data['cover_image']);
            }

            return redirect()->back()
                ->with('error', 'Failed to create group. Please try again.')
                ->withInput();
        }
    }

    public function show(Group $group)
    {
        if (!$group->canView(Auth::user())) {
            abort(403, 'You do not have permission to view this group.');
        }

        $group->load([
            'owner.profile',
            'admins.profile',
            'pinnedPosts' => function ($q) {
                $q->with('user.profile')
                    ->withCount('likes', 'comments')
                    ->latest();
            }
        ]);

        $posts = GroupPost::where('group_id', $group->id)
            ->with('user.profile')
            ->withCount('likes', 'comments')
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Add these for the tabs
        $resources = GroupResource::where('group_id', $group->id)
            ->with('user.profile')
            ->withCount('likes')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'resources_page');

        $events = GroupEvent::where('group_id', $group->id)
            ->with('user.profile')
            ->where('starts_at', '>=', now())
            ->orderBy('starts_at')
            ->paginate(10, ['*'], 'events_page');

        $members = $group->members()
            ->wherePivot('status', 'active')
            ->withPivot('role', 'joined_at')
            ->with('profile')
            ->orderByPivot('role', 'desc')
            ->orderByPivot('joined_at')
            ->paginate(12, ['*'], 'members_page');

        $membersCount = $group->members()->wherePivot('status', 'active')->count();
        $onlineMembers = 0; // Implement later
        $userRole = $group->member_role;

        $pendingRequests = $group->canManage(Auth::user())
            ? $group->pendingMembers()->with('profile')->get()
            : collect();

        return view('groups.show', compact(
            'group',
            'posts',
            'resources',
            'events',
            'members',
            'membersCount',
            'onlineMembers',
            'userRole',
            'pendingRequests'
        ));
    }


    public function edit(Group $group)
    {
        // $this->authorize('update', $group);

        $categories = [
            'tech-stack' => 'Tech Stack',
            'location' => 'Location',
            'interest' => 'Interest',
            'project' => 'Project',
            'learning' => 'Learning',
        ];

        return view('groups.edit', compact('group', 'categories'));
    }

    public function update(Request $request, Group $group)
    {
        // $this->authorize('update', $group);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:groups,name,' . $group->id,
            'description' => 'required|string|max:2000',
            'category' => 'required|in:tech-stack,location,interest,project,learning',
            'tags' => 'nullable|string|max:500',
            'privacy' => 'required|in:public,private,hidden',
            'member_approval' => 'required|in:anyone,admin_approval,invite_only',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'remove_icon' => 'boolean',
            'remove_cover' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $data = $validator->validated();

            // Handle icon
            if ($request->hasFile('icon')) {
                if ($group->icon) {
                    Storage::disk('public')->delete($group->icon);
                }
                $data['icon'] = $request->file('icon')->store('groups/icons', 'public');
            } elseif ($request->remove_icon) {
                if ($group->icon) {
                    Storage::disk('public')->delete($group->icon);
                }
                $data['icon'] = null;
            }

            // Handle cover image
            if ($request->hasFile('cover_image')) {
                if ($group->cover_image) {
                    Storage::disk('public')->delete($group->cover_image);
                }
                $data['cover_image'] = $request->file('cover_image')->store('groups/covers', 'public');
            } elseif ($request->remove_cover) {
                if ($group->cover_image) {
                    Storage::disk('public')->delete($group->cover_image);
                }
                $data['cover_image'] = null;
            }

            // Process tags
            $data['tags'] = $request->tags ? array_map('trim', explode(',', $request->tags)) : [];

            $group->update($data);

            DB::commit();

            return redirect()->route('groups.show', $group->slug)
                ->with('success', 'Group updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to update group. Please try again.')
                ->withInput();
        }
    }

    public function destroy(Group $group)
    {
        // $this->authorize('delete', $group);

        DB::beginTransaction();
        try {
            // Delete files
            if ($group->icon) {
                Storage::disk('public')->delete($group->icon);
            }
            if ($group->cover_image) {
                Storage::disk('public')->delete($group->cover_image);
            }

            $group->delete();

            DB::commit();

            return redirect()->route('groups.index')
                ->with('success', 'Group deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to delete group. Please try again.');
        }
    }

    public function myGroups()
    {
        $groups = Group::whereHas('members', function ($q) {
            $q->where('user_id', Auth::id())
                ->where('status', 'active');
        })
            ->with('owner.profile')
            ->withCount('members')
            ->orderBy('last_active_at', 'desc')
            ->get();

        $pendingRequests = Group::whereHas('members', function ($q) {
            $q->where('user_id', Auth::id())
                ->where('status', 'pending');
        })->get();

        return view('groups.my-groups', compact('groups', 'pendingRequests'));
    }

    // ============== MEMBERSHIP ==============

    public function join(Group $group)
    {
        // Check if already member or pending
        $existing = $group->members()
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            if ($existing->pivot->status === 'active') {
                return redirect()->back()->with('info', 'You are already a member of this group.');
            }
            if ($existing->pivot->status === 'pending') {
                return redirect()->back()->with('info', 'Your membership request is pending approval.');
            }
        }

        // Add member
        $group->addMember(Auth::user());

        $message = $group->member_approval === 'anyone'
            ? 'You have joined the group!'
            : 'Your request to join has been sent for approval.';

        return redirect()->back()->with('success', $message);
    }

    public function leave(Group $group)
    {
        $group->removeMember(Auth::user());
        return redirect()->route('groups.show', $group->slug)
            ->with('success', 'You have left the group.');
    }

    public function members(Group $group)
    {
        if (!$group->canView(Auth::user())) {
            abort(403);
        }

        $members = $group->members()
            ->wherePivot('status', 'active')
            ->withPivot('role', 'joined_at')
            ->with('profile')
            ->orderByPivot('role', 'desc')
            ->orderByPivot('joined_at')
            ->paginate(24);

        $admins = $group->admins()
            ->with('profile')
            ->get();

        $pendingRequests = collect();
        if ($group->canManage(Auth::user())) {
            $pendingRequests = $group->pendingMembers()
                ->with('profile')
                ->get();
        }

        return view('groups.members', compact('group', 'members', 'admins', 'pendingRequests'));
    }

    public function approveMember(Group $group, User $user)
    {
        // $this->authorize('manage', $group);
        $group->approveMember($user, Auth::user());
        return redirect()->back()->with('success', $user->name . ' has been approved to join.');
    }

    public function rejectMember(Group $group, User $user)
    {
        // $this->authorize('manage', $group);
        $group->members()->detach($user->id);
        $group->decrement('pending_requests');
        return redirect()->back()->with('success', 'Membership request rejected.');
    }

    public function removeMember(Group $group, User $user)
    {
        // $this->authorize('manage', $group);
        $group->members()->detach($user->id);
        $group->decrement('members_count');
        return redirect()->back()->with('success', $user->name . ' has been removed from the group.');
    }

    public function updateMemberRole(Request $request, Group $group, User $user)
    {
        // $this->authorize('manage', $group);

        $validator = Validator::make($request->all(), [
            'role' => 'required|in:admin,moderator,member',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $group->updateMemberRole($user, $request->role, Auth::user());
        return redirect()->back()->with('success', $user->name . '\'s role updated to ' . $request->role);
    }

    // ============== INVITATIONS ==============

    public function invite(Request $request, Group $group)
    {
        // $this->authorize('manage', $group);

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'message' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $token = Str::random(32);
        GroupInvitation::create([
            'group_id' => $group->id,
            'inviter_id' => Auth::id(),
            'email' => $request->email,
            'token' => $token,
            'message' => $request->message,
            'expires_at' => now()->addDays(7),
        ]);

        // TODO: Send email notification

        return redirect()->back()->with('success', 'Invitation sent successfully!');
    }

    public function acceptInvitation($token)
    {
        $invitation = GroupInvitation::where('token', $token)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->firstOrFail();

        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('info', 'Please login to accept the invitation.');
        }

        $user = User::where('email', $invitation->email)->first();

        if (!$user) {
            return redirect()->route('register')
                ->with('info', 'Please create an account to join the group.');
        }

        if ($invitation->group->isMember) {
            $invitation->update(['status' => 'accepted', 'responded_at' => now()]);
            return redirect()->route('groups.show', $invitation->group->slug)
                ->with('info', 'You are already a member of this group.');
        }

        $invitation->group->addMember($user, 'member', $invitation->inviter);
        $invitation->update([
            'status' => 'accepted',
            'responded_at' => now(),
            'user_id' => $user->id,
        ]);

        return redirect()->route('groups.show', $invitation->group->slug)
            ->with('success', 'You have joined the group!');
    }

    // ============== GROUP POSTS ==============

    public function storePost(Request $request, Group $group)
    {
        if (!$group->canPost(Auth::user())) {
            abort(403, 'You do not have permission to post in this group.');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:general,announcement,question,resource,event,job',
            'attachments.*' => 'file|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['user_id'] = Auth::id();
        $data['group_id'] = $group->id;

        // Handle file attachments
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('groups/posts/' . $group->id, 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                    'type' => $file->getMimeType(),
                ];
            }
            $data['attachments'] = $attachments;
        }

        $post = GroupPost::create($data);
        $group->increment('posts_count');

        return redirect()->route('groups.post', [$group->slug, $post->id])
            ->with('success', 'Post created successfully!');
    }

    public function showPost(Group $group, $postId)
    {
        $post = GroupPost::with([
            'user.profile',
            'comments' => function ($q) {
                $q->with('user.profile', 'replies.user.profile')
                    ->orderBy('created_at', 'desc');
            },
            'likes'
        ])->findOrFail($postId);

        if ($post->group_id !== $group->id) {
            abort(404);
        }

        return view('groups.posts', compact('group', 'post'));
    }

    public function likePost(Request $request, Group $group, GroupPost $post)
    {
        if ($post->group_id !== $group->id) {
            abort(404);
        }

        $like = GroupPostLike::where('group_post_id', $post->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($like) {
            $like->delete();
            $post->decrement('likes_count');
            $liked = false;
        } else {
            GroupPostLike::create([
                'group_post_id' => $post->id,
                'user_id' => Auth::id(),
            ]);
            $post->increment('likes_count');
            $liked = true;
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'liked' => $liked,
                'count' => $post->fresh()->likes_count,
            ]);
        }

        return redirect()->back();
    }

    public function pinPost(Group $group, GroupPost $post)
    {
        // $this->authorize('manage', $group);

        $post->update([
            'is_pinned' => true,
            'pinned_until' => now()->addDays(7),
        ]);

        return redirect()->back()->with('success', 'Post pinned successfully!');
    }

    public function unpinPost(Group $group, GroupPost $post)
    {
        // $this->authorize('manage', $group);

        $post->update([
            'is_pinned' => false,
            'pinned_until' => null,
        ]);

        return redirect()->back()->with('success', 'Post unpinned successfully!');
    }

    // ============== RESOURCES ==============

    public function resources(Group $group)
    {
        if (!$group->canView(Auth::user())) {
            abort(403);
        }

        $resources = GroupResource::where('group_id', $group->id)
            ->with('user.profile')
            ->withCount('likes')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $types = [
            'link' => '🔗 Link',
            'file' => '📁 File',
            'code' => '💻 Code',
            'tutorial' => '📖 Tutorial',
            'tool' => '🛠️ Tool',
            'book' => '📚 Book',
        ];

        return view('groups.resources', compact('group', 'resources', 'types'));
    }

    public function storeResource(Request $request, Group $group)
    {
        if (!$group->isMember) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:link,file,code,tutorial,tool,book',
            'url' => 'required_if:type,link|nullable|url',
            'file' => 'required_if:type,file|nullable|file|max:25600',
            'code' => 'required_if:type,code|nullable|string',
            'language' => 'required_if:type,code|nullable|string',
            'tags' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['user_id'] = Auth::id();
        $data['group_id'] = $group->id;
        $data['tags'] = $request->tags ? array_map('trim', explode(',', $request->tags)) : [];

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('groups/resources/' . $group->id, 'public');
            $data['metadata'] = [
                'size' => $request->file('file')->getSize(),
                'mime' => $request->file('file')->getMimeType(),
                'original_name' => $request->file('file')->getClientOriginalName(),
            ];
        }

        if ($request->type === 'code' && $request->code) {
            $data['metadata'] = [
                'code' => $request->code,
                'language' => $request->language,
            ];
        }

        GroupResource::create($data);

        return redirect()->back()->with('success', 'Resource added successfully!');
    }

    // ============== EVENTS ==============

    public function events(Group $group)
    {
        if (!$group->canView(Auth::user())) {
            abort(403);
        }

        $events = GroupEvent::where('group_id', $group->id)
            ->with('user.profile')
            ->orderBy('starts_at')
            ->paginate(12);

        return view('groups.events', compact('group', 'events'));
    }

    public function storeEvent(Request $request, Group $group)
    {
        if (!$group->isMember) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:meetup,hackathon,workshop,webinar,social',
            'format' => 'required|in:online,in_person,hybrid',
            'location' => 'required_if:format,in_person,hybrid|nullable|string|max:500',
            'meeting_link' => 'required_if:format,online,hybrid|nullable|url|max:500',
            'starts_at' => 'required|date|after:now',
            'ends_at' => 'nullable|date|after:starts_at',
            'max_attendees' => 'nullable|integer|min:1|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['user_id'] = Auth::id();
        $data['group_id'] = $group->id;
        $data['attendees'] = [];

        GroupEvent::create($data);

        return redirect()->route('groups.events', $group->slug)
            ->with('success', 'Event created successfully!');
    }

    public function attendEvent(Group $group, GroupEvent $event)
    {
        if (!$group->isMember) {
            abort(403);
        }

        $attendees = $event->attendees ?? [];

        if (in_array(Auth::id(), $attendees)) {
            $attendees = array_diff($attendees, [Auth::id()]);
            $message = 'You are no longer attending this event.';
        } else {
            if ($event->max_attendees && count($attendees) >= $event->max_attendees) {
                return redirect()->back()->with('error', 'This event has reached maximum capacity.');
            }
            $attendees[] = Auth::id();
            $message = 'You are now attending this event!';
        }

        $event->update([
            'attendees' => array_values($attendees),
            'attendees_count' => count($attendees),
        ]);

        return redirect()->back()->with('success', $message);
    }

    // ============== DISCOVERY ==============

    public function discover()
    {
        $recommended = Group::where('privacy', 'public')
            ->withCount('members')
            ->orderBy('members_count', 'desc')
            ->limit(6)
            ->get();

        $recent = Group::where('privacy', 'public')
            ->withCount('members')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        $categories = [
            'tech-stack' => '💻 Tech Stack',
            'location' => '📍 Location',
            'interest' => '🎯 Interest',
            'project' => '🚀 Project',
            'learning' => '📚 Learning',
        ];

        return view('groups.discover', compact('recommended', 'recent', 'categories'));
    }

    public function trending()
    {
        $groups = Group::where('privacy', 'public')
            ->withCount('members')
            ->where('last_active_at', '>=', now()->subDays(7))
            ->orderBy('members_count', 'desc')
            ->paginate(12);

        return view('groups.trending', compact('groups'));
    }

    public function category($category)
    {
        $groups = Group::where('privacy', 'public')
            ->where('category', $category)
            ->withCount('members')
            ->orderBy('members_count', 'desc')
            ->paginate(12);

        $categoryName = match ($category) {
            'tech-stack' => 'Tech Stack',
            'location' => 'Location',
            'interest' => 'Interest',
            'project' => 'Project',
            'learning' => 'Learning',
            default => $category,
        };

        return view('groups.category', compact('groups', 'category', 'categoryName'));
    }

    public function recommended()
    {
        // Simple recommendation based on user's interests
        $groups = Group::where('privacy', 'public')
            ->withCount('members')
            ->orderBy('members_count', 'desc')
            ->orderBy('last_active_at', 'desc')
            ->limit(10)
            ->get();

        return view('groups.recommended', compact('groups'));
    }

    public function activity(Group $group)
    {
        // $this->authorize('view', $group);

        $activities = $group->activityLogs()
            ->with('user.profile')
            ->paginate(30);

        return view('groups.activity', compact('group', 'activities'));
    }

    public function storeComment(Request $request, Group $group, GroupPost $post)
    {
        if (!$group->is_member) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:group_post_comments,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $comment = GroupPostComment::create([
            'group_post_id' => $post->id,
            'user_id' => Auth::id(),
            'parent_id' => $request->parent_id,
            'content' => $request->content,
        ]);

        $post->increment('comments_count');

        return redirect()->back()->with('success', 'Comment added successfully!');
    }

    /**
     * Delete a comment
     */
    public function deleteComment(Group $group, GroupPostComment $comment)
    {
        $post = $comment->post;

        if ($comment->user_id !== Auth::id() && !$group->canManage(Auth::user())) {
            abort(403);
        }

        $comment->delete();
        $post->decrement('comments_count');

        return redirect()->back()->with('success', 'Comment deleted successfully!');
    }

    /**
     * Like a comment
     */
    public function likeComment(Request $request, Group $group, GroupPostComment $comment)
    {
        $like = GroupCommentLike::where('group_post_comment_id', $comment->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($like) {
            $like->delete();
            $comment->decrement('likes_count');
            $liked = false;
        } else {
            GroupCommentLike::create([
                'group_post_comment_id' => $comment->id,
                'user_id' => Auth::id(),
            ]);
            $comment->increment('likes_count');
            $liked = true;
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'liked' => $liked,
                'count' => $comment->fresh()->likes_count,
            ]);
        }

        return redirect()->back();
    }

    /**
     * Download a resource file
     */
    public function downloadResource(Group $group, GroupResource $resource)
    {
        if (!$group->canView(Auth::user())) {
            abort(403);
        }

        if (!$resource->file_path || !Storage::disk('public')->exists($resource->file_path)) {
            abort(404);
        }

        $resource->increment('downloads_count');

        return Storage::disk('public')->download($resource->file_path);
    }

    /**
     * Delete a resource
     */
    public function deleteResource(Group $group, GroupResource $resource)
    {
        if ($resource->user_id !== Auth::id() && !$group->canManage(Auth::user())) {
            abort(403);
        }

        if ($resource->file_path) {
            Storage::disk('public')->delete($resource->file_path);
        }

        $resource->delete();

        return redirect()->back()->with('success', 'Resource deleted successfully!');
    }

    /**
     * Like a resource
     */
    public function likeResource(Request $request, Group $group, GroupResource $resource)
    {
        $like = GroupResourceLike::where('group_resource_id', $resource->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($like) {
            $like->delete();
            $resource->decrement('likes_count');
            $liked = false;
        } else {
            GroupResourceLike::create([
                'group_resource_id' => $resource->id,
                'user_id' => Auth::id(),
            ]);
            $resource->increment('likes_count');
            $liked = true;
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'liked' => $liked,
                'count' => $resource->fresh()->likes_count,
            ]);
        }

        return redirect()->back();
    }

    /**
     * Delete an event
     */
    public function deleteEvent(Group $group, GroupEvent $event)
    {
        if ($event->user_id !== Auth::id() && !$group->canManage(Auth::user())) {
            abort(403);
        }

        $event->delete();

        return redirect()->back()->with('success', 'Event deleted successfully!');
    }

    /**
     * Resend invitation (optional)
     */
    public function resendInvitation(Group $group, GroupInvitation $invitation)
    {
        // $this->authorize('manage', $group);

        $invitation->update([
            'expires_at' => now()->addDays(7),
            'status' => 'pending',
        ]);

        // TODO: Resend email

        return redirect()->back()->with('success', 'Invitation resent successfully!');
    }

    /**
     * Cancel invitation
     */
    public function cancelInvitation(Group $group, GroupInvitation $invitation)
    {
        // $this->authorize('manage', $group);

        $invitation->update(['status' => 'expired']);

        return redirect()->back()->with('success', 'Invitation cancelled successfully!');
    }

    /**
     * Update general settings
     */
    public function updateGeneralSettings(Request $request, Group $group)
    {
        // $this->authorize('manage', $group);

        $validator = Validator::make($request->all(), [
            'post_permission' => 'required|in:all_members,admins_only',
            'comment_permission' => 'required|in:all_members,members_only',
            'resource_sharing' => 'boolean',
            'allow_events' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $settings = array_merge($group->settings ?? [], $validator->validated());
        $group->update(['settings' => $settings]);

        return redirect()->back()->with('success', 'Group settings updated successfully!');
    }

    /**
     * Update permissions
     */
    public function updatePermissions(Request $request, Group $group)
    {
        // $this->authorize('manage', $group);

        $validator = Validator::make($request->all(), [
            'privacy' => 'required|in:public,private,hidden',
            'member_approval' => 'required|in:anyone,admin_approval,invite_only',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $group->update($validator->validated());

        return redirect()->back()->with('success', 'Group permissions updated successfully!');
    }

    /**
     * Transfer ownership
     */
    public function transferOwnership(Request $request, Group $group)
    {
        if (Auth::id() !== $group->owner_id) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $newOwner = User::find($request->user_id);

        if (!$group->isMember || $newOwner->id === $group->owner_id) {
            return redirect()->back()->with('error', 'Invalid user or user is not a member.');
        }

        // Update current owner to admin
        $group->members()->updateExistingPivot($group->owner_id, ['role' => 'admin']);

        // Set new owner
        $group->members()->updateExistingPivot($newOwner->id, ['role' => 'owner']);
        $group->update(['owner_id' => $newOwner->id]);

        return redirect()->back()->with('success', 'Group ownership transferred successfully!');
    }

    /**
     * Report a group
     */
    public function report(Request $request, Group $group)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required|string|max:500',
            'details' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        // TODO: Create reports table and model
        // For now, just log it
        \Log::warning('Group reported', [
            'group_id' => $group->id,
            'user_id' => Auth::id(),
            'reason' => $request->reason,
            'details' => $request->details,
        ]);

        return redirect()->back()->with('success', 'Group reported successfully. Our team will review it.');
    }

    /**
     * Edit post (if needed)
     */
    /**
     * Edit post
     */
    public function editPost(Group $group, GroupPost $post)
    {
        if ($post->user_id !== Auth::id() && !$group->canManage(Auth::user())) {
            abort(403, 'You do not have permission to edit this post.');
        }

        if ($post->group_id !== $group->id) {
            abort(404);
        }

        $postTypes = [
            'general' => '📝 General',
            'announcement' => '📢 Announcement',
            'question' => '❓ Question',
            'resource' => '📚 Resource',
            'event' => '📅 Event',
            'job' => '💼 Job',
        ];

        return view('groups.edit-post', compact('group', 'post', 'postTypes'));
    }
    /**
     * Update post
     */
    public function updatePost(Request $request, Group $group, GroupPost $post)
    {
        if ($post->user_id !== Auth::id() && !$group->canManage(Auth::user())) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:general,announcement,question,resource,event,job',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $post->update($validator->validated());

        return redirect()->route('groups.post', [$group->slug, $post->id])
            ->with('success', 'Post updated successfully!');
    }

    /**
     * Delete post
     */
    public function deletePost(Group $group, GroupPost $post)
    {
        if ($post->user_id !== Auth::id() && !$group->canManage(Auth::user())) {
            abort(403);
        }

        // Delete attachments
        if ($post->attachments) {
            foreach ($post->attachments as $attachment) {
                Storage::disk('public')->delete($attachment['path']);
            }
        }

        $post->delete();
        $group->decrement('posts_count');

        return redirect()->route('groups.show', $group->slug)
            ->with('success', 'Post deleted successfully!');
    }


    /**
     * Mark post as important
     */
    public function markImportant(Group $group, GroupPost $post)
    {
        if (!$group->canManage(Auth::user())) {
            abort(403, 'You do not have permission to mark posts as important.');
        }

        if ($post->group_id !== $group->id) {
            abort(404);
        }

        $post->update([
            'is_important' => true,
        ]);

        $group->logActivity(
            Auth::user(),
            'post_marked_important',
            Auth::user()->name . ' marked post as important: ' . $post->title
        );

        return redirect()->back()->with('success', 'Post marked as important!');
    }

    /**
     * Unmark post as important
     */
    public function unmarkImportant(Group $group, GroupPost $post)
    {
        if (!$group->canManage(Auth::user())) {
            abort(403, 'You do not have permission to unmark posts as important.');
        }

        if ($post->group_id !== $group->id) {
            abort(404);
        }

        $post->update([
            'is_important' => false,
        ]);

        $group->logActivity(
            Auth::user(),
            'post_unmarked_important',
            Auth::user()->name . ' unmarked post as important: ' . $post->title
        );

        return redirect()->back()->with('success', 'Post unmarked as important!');
    }

    /**
     * Update comment
     */
    public function updateComment(Request $request, Group $group, GroupPostComment $comment)
    {
        if ($comment->user_id !== Auth::id() && !$group->canManage(Auth::user())) {
            abort(403, 'You do not have permission to edit this comment.');
        }

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $comment->update($validator->validated());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'content' => $comment->content,
                'message' => 'Comment updated successfully!'
            ]);
        }

        return redirect()->back()->with('success', 'Comment updated successfully!');
    }
}
