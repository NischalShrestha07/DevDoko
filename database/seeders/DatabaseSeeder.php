<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\Tag;
use App\Models\TechTag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            TagSeeder::class,
            MarketplaceCategorySeeder::class,
        ]);

        $admin = User::create([
            'name' => 'Anup Ghimire',
            'email' => 'anup.ghimire@devdoko.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        $admin->profile->update([
            'username' => 'anupghimire',
            'bio' => 'Full-stack developer from Kathmandu. Passionate about Laravel and building developer communities in Nepal.',
            'github_link' => 'https://github.com/anupghimire',
        ]);

        $users = User::factory(10)->create();

        $techTags = [
            'Laravel', 'PHP', 'JavaScript', 'React', 'Vue.js', 'Node.js',
            'Python', 'Django', 'Java', 'Spring Boot', 'C#', '.NET',
            'Ruby', 'Rails', 'Go', 'Rust', 'Swift', 'Kotlin',
            'MySQL', 'PostgreSQL', 'MongoDB', 'Redis', 'Docker', 'AWS',
        ];

        foreach ($techTags as $tagName) {
            TechTag::firstOrCreate(['name' => $tagName]);
        }

        $allTags = Tag::all();

        $users = User::with('profile')->get();

        $nepaliCommentTemplates = [
            'Dherai ramro post! Malaai ekdum mitho lago.',
            'Ma pani yehi topic ma kaam garirako thiye. Thanks for sharing!',
            'Ekdam useful info. Thanks bro.',
            'Yo ta mero lagi ekdum helpful rahecha. Keep posting!',
            'Khoi ma chai yetro bela samma bujhna sakena. Thanks for explaining.',
            'Ma ni Laravel sikeko xu. Specially route ra blade ko concept ramro cha.',
            'Dherai dherai ramro. Hami ni team ma milera project garna sakchau ki?',
            'React 19 ma k k naya aako cha? Maile fresh suru garna lageko.',
            'Timi kasto platform haru use garchau deployment ko lagi?',
            'Esma aru option pani hola ni? Tara overall ramro cha.',
        ];

        $nepaliPostContents = [
            'Aaja ma Vercel ma Laravel app deploy garna sike. Yo process dherai simple rahecha. Pahila Vapor socheko thiye tera Vercel pani ramro option ho. Maile laravel-forge + Vercel combo use gareko chu.',
            'Laravel 12 ma naya aako features: - Native auth scaffolding update - New artisan commands - Better API development workflow. Malaai special chai model casting ko improvements ramro lagyo.',
            'Hamile Nepali developers lai ni open source ma contribute garna sikna paryo. Ma suru garna lageko chu ek github repo jasma Nepali developers ko lagi beginner-friendly issues haru huncha.',
            'What are the best practices for structuring a Laravel API? I always get confused between service-repository pattern vs just using models. Anyone have experience with this in production?',
            'Today I discovered that you can use Alpine.js with Laravel without writing any JavaScript. Its amazing how simple the reactive UI becomes. Lifetime is a game changer.',
            'React VS Vue for Nepali developers — which one should you focus on in 2026? Based on job market in Nepal, React has more opportunities but Vue is easier to start with. What do you think?',
            'Maile aaja Docker suru gare. Yetro sajilo rahecha. Pahila kasto complicated lagthyo. Desktop app install garera nai test garna milne rahecha.',
            'Hamro team ma Agile methodology implement garna lageko chu. Daily standup, sprint planning, retrospective sabai Nepali team ma kaam garcha?',
            'GitHub Actions vs GitLab CI vs Jenkins — k ko use garne for side projects? Ma personally GitHub Actions use garchu because of simplicity and free tier.',
            'Database optimization tips for slow Laravel queries: - Use eager loading - Add indexes - Use query caching. Mero app 3x faster vayo after implementing these.',
        ];

        $questionTitles = [
            'Laravel queue job fail vayo. Kasari debug garne?',
            'Nepal ma remote job pauxa ki pauxena for mid-level devs?',
            'React ma state manage garne best approach?',
            'Docker ma permission denied error aaairacha. Solution?',
            'Authentication option haru: ma Sanctum ki Passport?',
            'VS Code ki PHPStorm? Mero lagi confusion bhairacha.',
            'Nepali developers ko lagi best hosting options?',
            'Ma Laravel developer. Kati salary expect garna milcha?',
        ];

        foreach ($users as $user) {
            $randomTechTags = TechTag::inRandomOrder()->limit(5)->pluck('id');
            if ($user->profile && $user->profile->exists) {
                $user->profile->techTags()->attach($randomTechTags);
            }

            $posts = Post::factory(rand(3, 6))->create(['user_id' => $user->id]);

            foreach ($posts as $postIndex => $post) {
                if ($post->type === 'question' && $questionTitles) {
                    $title = $questionTitles[array_rand($questionTitles)];
                    $post->update(['title' => $title, 'content' => $nepaliPostContents[array_rand($nepaliPostContents)]]);
                } elseif ($postIndex === 0) {
                    $post->update(['content' => $nepaliPostContents[array_rand($nepaliPostContents)]]);
                }

                if ($allTags->isNotEmpty()) {
                    $randomPostTags = $allTags->random(min(3, $allTags->count()))->pluck('id');
                    $post->tags()->attach($randomPostTags);
                }

                $likingUsers = User::where('id', '!=', $user->id)
                    ->inRandomOrder()->limit(rand(1, 5))->get();
                foreach ($likingUsers as $likingUser) {
                    Like::create(['user_id' => $likingUser->id, 'post_id' => $post->id]);
                }

                $commentingUsers = User::where('id', '!=', $user->id)
                    ->inRandomOrder()->limit(rand(1, 3))->get();
                foreach ($commentingUsers as $commentingUser) {
                    Comment::create([
                        'user_id' => $commentingUser->id,
                        'post_id' => $post->id,
                        'content' => $nepaliCommentTemplates[array_rand($nepaliCommentTemplates)],
                    ]);
                }

                $post->update([
                    'likes_count' => $post->likes()->count(),
                    'comments_count' => $post->comments()->count(),
                ]);
            }
        }

        foreach ($users as $user) {
            $usersToFollow = User::where('id', '!=', $user->id)
                ->inRandomOrder()->limit(rand(2, 5))->get();
            foreach ($usersToFollow as $userToFollow) {
                try {
                    $user->following()->attach($userToFollow->id);
                } catch (\Illuminate\Database\QueryException $e) {
                    //
                }
            }
        }

        $this->call([
            NotificationSeeder::class,
            ProjectSeeder::class,
            JobSeeder::class,
            GroupSeeder::class,
            MessageSeeder::class,
            MarketplaceListingSeeder::class,
        ]);
    }
}
