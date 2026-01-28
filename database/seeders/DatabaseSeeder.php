<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\Tag;
use App\Models\TechTag;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    // database/seeders/DatabaseSeeder.php
    public function run()
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        $admin->profile->update([
            'username' => 'admin',
            'bio' => 'System Administrator',
            'github_link' => 'https://github.com/admin',
        ]);

        // Create regular users
        $users = User::factory(10)->create();

        // Create tech tags
        $techTags = [
            'Laravel',
            'PHP',
            'JavaScript',
            'React',
            'Vue.js',
            'Node.js',
            'Python',
            'Django',
            'Java',
            'Spring Boot',
            'C#',
            '.NET',
            'Ruby',
            'Rails',
            'Go',
            'Rust',
            'Swift',
            'Kotlin',
            'MySQL',
            'PostgreSQL',
            'MongoDB',
            'Redis',
            'Docker',
            'AWS'
        ];

        foreach ($techTags as $tagName) {
            TechTag::create(['name' => $tagName]);
        }

        // Create posts for each user
        foreach ($users as $user) {
            // Attach random tech tags to profile
            $randomTags = TechTag::inRandomOrder()->limit(5)->pluck('id');
            $user->profile->techTags()->attach($randomTags);

            // Create posts
            $posts = Post::factory(rand(3, 8))->create([
                'user_id' => $user->id,
            ]);

            foreach ($posts as $post) {
                // Add random tags to posts
                $randomPostTags = Tag::inRandomOrder()->limit(3)->pluck('id');
                $post->tags()->attach($randomPostTags);

                // Add likes
                $likingUsers = User::where('id', '!=', $user->id)
                    ->inRandomOrder()
                    ->limit(rand(1, 5))
                    ->get();

                foreach ($likingUsers as $likingUser) {
                    Like::create([
                        'user_id' => $likingUser->id,
                        'post_id' => $post->id,
                    ]);
                }

                // Add comments
                $commentingUsers = User::where('id', '!=', $user->id)
                    ->inRandomOrder()
                    ->limit(rand(0, 3))
                    ->get();

                foreach ($commentingUsers as $commentingUser) {
                    Comment::create([
                        'user_id' => $commentingUser->id,
                        'post_id' => $post->id,
                        'content' => fake()->sentence(),
                    ]);
                }
            }
        }

        // Create follow relationships
        foreach ($users as $user) {
            $usersToFollow = User::where('id', '!=', $user->id)
                ->inRandomOrder()
                ->limit(rand(2, 5))
                ->get();

            foreach ($usersToFollow as $userToFollow) {
                $user->following()->attach($userToFollow->id);
            }
        }
    }
}
