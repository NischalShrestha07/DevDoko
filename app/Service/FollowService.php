<?php


class FollowService
{
    public function followUser($follower, $userToFollow)
    {
        if ($follower->id === $userToFollow->id) {
            throw new \Exception('Cannot follow yourself');
        }

        if ($follower->following()->where('following_id', $userToFollow->id)->exists()) {
            throw new \Exception('Already following');
        }

        $follower->following()->attach($userToFollow->id);

        // Add reputation for being followed
        $userToFollow->profile->addReputation('follow_received', 5);

        return true;
    }

    public function unfollowUser($follower, $userToUnfollow)
    {
        $follower->following()->detach($userToUnfollow->id);

        // Remove reputation
        $userToUnfollow->profile->addReputation('follow_lost', -5);
    }
}
