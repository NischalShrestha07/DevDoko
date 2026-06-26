<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    private static array $nepaliContent = [
        'Maile yehi article ma dherai kura sike. Specially service provider ko barema dherai ramro sanga bujhiyeko cha. Hamile Laravel ko internals bujhnu paryo productive hunako lagi.',
        'Database migration ko bela error aauna sakcha. Solution: php artisan migrate:fresh garera hernu. Yo command le sabai table drop garera new banauxa. Tera production ma careful huna paryo.',
        'Vue.js vs React — duitai ramro cha. Tara React ko job market Nepal ma comparatively bigger cha. Vue.js chai sikauna sajilo cha beginners lai hamro community ma.',
        'Aaja ko tip: Laravel scheduler use garera daily backup line garna milcha. Kernel.php ma scheduled task haru define garne ani server ma cron set garne. Easy backup solution.',
        'Tailwind CSS version 4 ma naya features: unified color system, improved dark mode. Ma recently upgrade gare Ani ekdum smooth experience rahecha. Highly recommend.',
        'Heyo guys! Maile connect garna khojeko developer haru sanga. Mero goal cha open source project contribute garne. Kasailai partnership cha collaboration ko lagi team ma?',
        'Git branching strategies: main -> develop -> feature. Yo workflow dherai companies ma use huncha. GitFlow vs trunk-based development. Nepal ma dherai jaso GitFlow use garchan.',
        'Testing ma ichcha cha switch garne. Currently manual testing garirako chu. PHPUnit basics sikna sakiyo. Integration testing ko lagi best practices suggest garna saknu huncha?',
    ];

    public function definition(): array
    {
        $types = ['text', 'code', 'image', 'question', 'project', 'status', 'link', 'article'];
        $type = $this->faker->randomElement($types);

        $content = match ($type) {
            'code' => "```php\n<?php\n\nnamespace App\\Http\\Controllers;\n\nclass PostController extends Controller\n{\n    public function index()\n    {\n        \$posts = Post::with('user', 'tags')\n            ->where('visibility', 'public')\n            ->latest()\n            ->paginate(10);\n\n        return view('posts.index', compact('posts'));\n    }\n}\n```",
            'question' => 'Maile laravel ma pagination garda query bigrincha. use `with()` ani `paginate()` sanga. Yesari garna milcha: `Post::with(\'user\')->paginate(10)`. TYo N+1 problem bata bachna paryo.',
            'status' => fake()->randomElement([
                'Aaja ma testing ko lagi unit test lekhdai chu',
                'Docker compose ma mysql connect garna sike aaja',
                'Naya project suru gardai chu. Laravel + React',
                'Job interview thyo aaja. Dherai ramro vayo.',
                'Open source contribution garna lageko ma',
            ]),
            default => self::$nepaliContent[array_rand(self::$nepaliContent)],
        };

        $title = match ($type) {
            'question' => fake()->randomElement([
                'Laravel queue fail vayo. Kasari debug garne?',
                'Nepal ma remote job pauxa for mid-level dev?',
                'React ma state manage garne best approach?',
                'Docker permission denied error — solution?',
                'Sanctum ki Passport — authentication ko lagi?',
                'Nepali devs ko lagi best hosting options?',
            ]),
            'code' => fake()->randomElement([
                'Laravel Controller example with eager loading',
                'Eloquent query scope for public posts',
                'Form request validation example',
                'Custom artisan command boilerplate',
            ]),
            default => fake()->boolean(60) ? fake()->randomElement([
                'Laravel best practices for 2026',
                'React ma state manage garne tarika',
                'Docker basic commands for beginners',
                'Nepal ma developer hoe build garne',
                'Database optimization tips',
                'Open source ma contribute kasari garne',
            ]) : null,
        };

        return [
            'user_id' => User::factory(),
            'type' => $type,
            'title' => $title,
            'content' => $content,
            'visibility' => $this->faker->randomElement(['public', 'public', 'public', 'followers']),
            'is_pinned' => false,
            'views_count' => $this->faker->numberBetween(5, 500),
            'likes_count' => 0,
            'comments_count' => 0,
            'shares_count' => $this->faker->numberBetween(0, 15),
            'reading_time' => $this->faker->numberBetween(1, 10),
        ];
    }
}
