<?php
// app/Policies/GroupPolicy.php

namespace App\Policies;

use App\Models\Group;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
{
    use HandlesAuthorization;

    public function view(?User $user, Group $group)
    {
        return $group->canView($user);
    }

    public function create(User $user)
    {
        return true;
    }

    public function update(User $user, Group $group)
    {
        return $user->id === $group->owner_id ||
            $group->members()
            ->where('user_id', $user->id)
            ->wherePivot('role', 'admin')
            ->wherePivot('status', 'active')
            ->exists();
    }

    public function delete(User $user, Group $group)
    {
        return $user->id === $group->owner_id;
    }

    public function manage(User $user, Group $group)
    {
        if ($user->id === $group->owner_id) {
            return true;
        }

        return $group->members()
            ->where('user_id', $user->id)
            ->wherePivotIn('role', ['admin', 'moderator'])
            ->wherePivot('status', 'active')
            ->exists();
    }
}
