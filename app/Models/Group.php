<?php
// app/Models/Group.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Group extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'owner_id',
        'name',
        'slug',
        'description',
        'category',
        'tags',
        'icon',
        'cover_image',
        'privacy',
        'member_approval',
        'settings',
        'members_count',
        'posts_count',
        'pending_requests',
        'last_active_at',
    ];

    protected $casts = [
        'tags' => 'array',
        'settings' => 'array',
        'last_active_at' => 'datetime',
    ];

    protected $appends = [
        'is_member',
        'is_pending',
        'member_role',
        'icon_url',
        'cover_url',
        'category_label',
        'privacy_label',
    ];

    // Relationships
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'group_members')
            ->withPivot('id', 'role', 'status', 'joined_at', 'approved_at', 'approved_by', 'contributions_count', 'badges', 'settings')
            ->withTimestamps();
    }

    public function activeMembers()
    {
        return $this->belongsToMany(User::class, 'group_members')
            ->wherePivot('status', 'active')
            ->withPivot('role', 'joined_at', 'contributions_count', 'badges');
    }

    public function pendingMembers()
    {
        return $this->belongsToMany(User::class, 'group_members')
            ->wherePivot('status', 'pending')
            ->withPivot('created_at');
    }

    public function admins()
    {
        return $this->belongsToMany(User::class, 'group_members')
            ->wherePivotIn('role', ['owner', 'admin', 'moderator'])
            ->wherePivot('status', 'active')
            ->withPivot('role');
    }

    public function posts()
    {
        return $this->hasMany(GroupPost::class)->orderBy('created_at', 'desc');
    }

    public function pinnedPosts()
    {
        return $this->hasMany(GroupPost::class)
            ->where('is_pinned', true)
            ->orderBy('pinned_until', 'asc');
    }

    public function resources()
    {
        return $this->hasMany(GroupResource::class)->orderBy('created_at', 'desc');
    }

    public function events()
    {
        return $this->hasMany(GroupEvent::class)->orderBy('starts_at', 'asc');
    }

    public function upcomingEvents()
    {
        return $this->hasMany(GroupEvent::class)
            ->where('starts_at', '>', now())
            ->orderBy('starts_at', 'asc');
    }

    public function invitations()
    {
        return $this->hasMany(GroupInvitation::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(GroupActivityLog::class)->orderBy('created_at', 'desc');
    }

    // Accessors
    public function getIsMemberAttribute()
    {
        if (!Auth::check()) return false;

        return $this->members()
            ->where('user_id', Auth::id())
            ->wherePivot('status', 'active')
            ->exists();
    }

    public function getIsPendingAttribute()
    {
        if (!Auth::check()) return false;

        return $this->members()
            ->where('user_id', Auth::id())
            ->wherePivot('status', 'pending')
            ->exists();
    }

    public function getMemberRoleAttribute()
    {
        if (!Auth::check()) return null;

        $member = $this->members()
            ->where('user_id', Auth::id())
            ->wherePivot('status', 'active')
            ->first();

        return $member ? $member->pivot->role : null;
    }

    public function getIconUrlAttribute()
    {
        return $this->icon ? asset('storage/' . $this->icon) : null;
    }

    public function getCoverUrlAttribute()
    {
        return $this->cover_image ? asset('storage/' . $this->cover_image) : null;
    }

    public function getCategoryLabelAttribute()
    {
        return match ($this->category) {
            'tech-stack' => '💻 Tech Stack',
            'location' => '📍 Location',
            'interest' => '🎯 Interest',
            'project' => '🚀 Project',
            'learning' => '📚 Learning',
            default => '📌 Other',
        };
    }

    public function getPrivacyLabelAttribute()
    {
        return match ($this->privacy) {
            'public' => '🌍 Public',
            'private' => '🔒 Private',
            'hidden' => '👻 Hidden',
            default => '🌍 Public',
        };
    }

    // Methods
    public function addMember(User $user, string $role = 'member', ?User $approvedBy = null)
    {
        $this->members()->attach($user->id, [
            'role' => $role,
            'status' => $this->member_approval === 'anyone' ? 'active' : 'pending',
            'joined_at' => now(),
            'approved_at' => $this->member_approval === 'anyone' ? now() : null,
            'approved_by' => $approvedBy?->id,
            'settings' => json_encode(['notifications' => 'all']),
        ]);

        if ($this->member_approval === 'anyone') {
            $this->increment('members_count');
        } else {
            $this->increment('pending_requests');
        }

        $this->logActivity(
            $user,
            $this->member_approval === 'anyone' ? 'member_joined' : 'membership_requested',
            $user->name . ' ' . ($this->member_approval === 'anyone' ? 'joined' : 'requested to join') . ' the group'
        );

        return true;
    }

    public function approveMember(User $user, User $approvedBy)
    {
        $this->members()->updateExistingPivot($user->id, [
            'status' => 'active',
            'approved_at' => now(),
            'approved_by' => $approvedBy->id,
        ]);

        $this->increment('members_count');
        $this->decrement('pending_requests');

        $this->logActivity(
            $approvedBy,
            'member_approved',
            $user->name . ' was approved to join the group by ' . $approvedBy->name
        );
    }

    public function removeMember(User $user)
    {
        $this->members()->detach($user->id);
        $this->decrement('members_count');

        $this->logActivity(
            Auth::user(),
            'member_left',
            $user->name . ' left the group'
        );
    }

    public function updateMemberRole(User $user, string $role, User $updatedBy)
    {
        $this->members()->updateExistingPivot($user->id, [
            'role' => $role,
        ]);

        $this->logActivity(
            $updatedBy,
            'member_role_updated',
            $updatedBy->name . ' updated ' . $user->name . '\'s role to ' . $role
        );
    }

    public function canView(?User $user = null)
    {
        if ($this->privacy === 'public') {
            return true;
        }

        if (!$user) {
            return false;
        }

        if ($user->id === $this->owner_id) {
            return true;
        }

        if ($this->privacy === 'private' || $this->privacy === 'hidden') {
            return $this->isMember;
        }

        return false;
    }

    public function canPost(User $user)
    {
        if (!$this->isMember && $user->id !== $this->owner_id) {
            return false;
        }

        $settings = $this->settings ?? [];
        $postPermission = $settings['post_permission'] ?? 'all_members';

        if ($postPermission === 'all_members') {
            return true;
        }

        if ($postPermission === 'admins_only') {
            return in_array($this->member_role, ['owner', 'admin', 'moderator']);
        }

        return false;
    }

    public function canManage(User $user)
    {
        if ($user->id === $this->owner_id) {
            return true;
        }

        if ($this->isMember && in_array($this->member_role, ['admin', 'moderator'])) {
            return true;
        }

        return false;
    }

    public function logActivity(User $user, string $action, string $description = null, array $data = [])
    {
        return $this->activityLogs()->create([
            'user_id' => $user->id,
            'action' => $action,
            'description' => $description,
            'data' => $data,
            'ip_address' => request()->ip(),
        ]);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($group) {
            $group->slug = $group->slug ?: Str::slug($group->name);
            $group->settings = $group->settings ?: [
                'post_permission' => 'all_members',
                'comment_permission' => 'all_members',
                'resource_sharing' => true,
                'allow_events' => true,
            ];
        });

        static::created(function ($group) {
            $group->members()->attach($group->owner_id, [
                'role' => 'owner',
                'status' => 'active',
                'joined_at' => now(),
                'approved_at' => now(),
                'approved_by' => $group->owner_id,
                'settings' => json_encode(['notifications' => 'all']),
            ]);
        });
    }

    public function groupPosts()
    {
        return $this->hasMany(GroupPost::class);
    }

    public function groupResources()
    {
        return $this->hasMany(GroupResource::class);
    }

    public function groupEvents()
    {
        return $this->hasMany(GroupEvent::class);
    }

    public function groupInvitations()
    {
        return $this->hasMany(GroupInvitation::class);
    }

    public function groupActivityLogs()
    {
        return $this->hasMany(GroupActivityLog::class);
    }
}
