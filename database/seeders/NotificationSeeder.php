<?php
// database/seeders/NotificationSeeder.php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            // Create sample notifications
            Notification::create([
                'user_id' => $user->id,
                'from_user_id' => User::where('id', '!=', $user->id)->inRandomOrder()->first()->id,
                'type' => 'welcome',
                'message' => 'Welcome to DevDoko!',
                'data' => json_encode(['welcome' => true]),
                'read_at' => null,
                'created_at' => now(),
            ]);
        }
    }
}
