<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) return;

        // Welcome notification for each user
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'from_user_id' => null,
                'type' => 'welcome',
                'message' => 'Welcome to DevDoko! Complete your profile to get started.',
                'data' => json_encode(['welcome' => true]),
                'read_at' => $user->id === 1 ? now() : null,
                'created_at' => $user->created_at,
            ]);
        }

        // Like notifications
        foreach ($users->take(5) as $user) {
            $liker = $users->where('id', '!=', $user->id)->random();

            Notification::create([
                'user_id' => $user->id,
                'from_user_id' => $liker->id,
                'type' => 'like',
                'message' => $liker->name . ' liked your post',
                'data' => json_encode(['post_id' => rand(1, 10)]),
                'read_at' => rand(0, 1) ? now()->subHours(rand(1, 24)) : null,
                'created_at' => now()->subDays(rand(0, 7)),
            ]);
        }

        // Follow notifications
        foreach ($users->take(4) as $user) {
            $follower = $users->where('id', '!=', $user->id)->random();

            Notification::create([
                'user_id' => $user->id,
                'from_user_id' => $follower->id,
                'type' => 'follow',
                'message' => $follower->name . ' started following you',
                'data' => json_encode([]),
                'read_at' => null,
                'created_at' => now()->subDays(rand(0, 5)),
            ]);
        }

        // Comment notifications
        foreach ($users->take(3) as $user) {
            $commenter = $users->where('id', '!=', $user->id)->random();

            Notification::create([
                'user_id' => $user->id,
                'from_user_id' => $commenter->id,
                'type' => 'comment',
                'message' => $commenter->name . ' commented on your post',
                'data' => json_encode(['post_id' => rand(1, 10), 'comment_id' => rand(1, 20)]),
                'read_at' => rand(0, 1) ? now()->subHours(rand(1, 12)) : null,
                'created_at' => now()->subDays(rand(0, 3)),
            ]);
        }
    }
}
