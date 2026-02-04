<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Comment $comment): bool
    {
        return $comment->post->canView($user);
    }

    public function create(User $user): bool
    {
        return $user->hasVerifiedEmail();
    }

    public function update(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id || $user->is_admin;
    }

    public function delete(User $user, Comment $comment): bool
    {
        return $user->id === $comment->user_id ||
            $user->id === $comment->post->user_id ||
            $user->is_admin;
    }
}
