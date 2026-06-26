<?php

namespace Database\Seeders;

use App\Models\MarketplaceCategory;
use App\Models\MarketplaceListing;
use App\Models\User;
use Illuminate\Database\Seeder;

class MarketplaceListingSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $categories = MarketplaceCategory::whereNotNull('parent_id')->get();

        if ($categories->isEmpty()) {
            $categories = MarketplaceCategory::all();
        }

        $listings = [
            [
                'title' => 'MacBook Air M1 (2021) - 16GB/256GB',
                'description' => 'Used MacBook Air M1 in excellent condition. 16GB RAM, 256GB SSD. Original charger includes. Battery health 89%. KTM ma dekhauna milcha.',
                'price' => 85000,
                'price_type' => 'negotiable',
                'condition' => 'good',
                'brand' => 'Apple',
                'model' => 'MacBook Air M1',
                'specifications' => ['Processor' => 'Apple M1', 'RAM' => '16GB', 'Storage' => '256GB SSD', 'Battery' => '89%'],
            ],
            [
                'title' => 'Mechanical Keyboard - Royal Kludge RK61',
                'description' => 'Royal Kludge RK61 60% mechanical keyboard. Red switches, RGB backlight, Bluetooth/USB dual mode. 2 months use matra.',
                'price' => 4500,
                'price_type' => 'fixed',
                'condition' => 'like_new',
                'brand' => 'Royal Kludge',
                'model' => 'RK61',
                'specifications' => ['Switch' => 'Red', 'Layout' => '60%', 'Connection' => 'Bluetooth + USB-C', 'Backlight' => 'RGB'],
            ],
            [
                'title' => 'Laravel: Up & Running - 2nd Edition (Used)',
                'description' => 'Like new condition. Covers Laravel 8-10. Ekdum ramro book ho beginners ra intermediate duitai lai. KTM ma pickup available.',
                'price' => 1200,
                'price_type' => 'fixed',
                'condition' => 'like_new',
                'brand' => 'O\'Reilly',
                'specifications' => ['Author' => 'Matt Stauffer', 'Edition' => '2nd', 'Language' => 'English'],
            ],
            [
                'title' => 'Dell 27" 4K Monitor - U2723QE',
                'description' => 'Dell Ultrasharp U2723QE 27-inch 4K IPS monitor. Coding ra design duitai ko lagi excellent. USB-C hub built-in (95W power delivery).',
                'price' => 62000,
                'price_type' => 'negotiable',
                'condition' => 'good',
                'brand' => 'Dell',
                'model' => 'U2723QE',
                'specifications' => ['Size' => '27-inch', 'Resolution' => '3840x2160', 'Panel' => 'IPS Black', 'Ports' => 'USB-C 95W, HDMI, DP'],
            ],
            [
                'title' => 'Freelance Web Dev Services - Nepali Dev',
                'description' => 'Ma ek freelance web developer hu. Laravel, Vue.js, Tailwind CSS ma specialize cha. E-commerce, SaaS, CMS banai dinchu. KTM based, online bhetna milcha.',
                'price' => 15000,
                'price_type' => 'negotiable',
                'condition' => null,
                'brand' => null,
            ],
            [
                'title' => 'PHPStorm License - 1 Year (Unused)',
                'description' => 'PHPStorm 1-year personal license. Aile matra kineko tara ma VS Code use garne vayeko, bechna lagako. E-mail ma milcha.',
                'price' => 8500,
                'price_type' => 'fixed',
                'condition' => 'new',
                'brand' => 'JetBrains',
                'specifications' => ['Validity' => '1 Year', 'Product' => 'PHPStorm', 'Type' => 'Personal License'],
            ],
            [
                'title' => 'Electric Standing Desk - FlexiSpot E7',
                'description' => 'Electric height-adjustable desk. 140x70cm bamboo top. Dual motor, programmable height settings. 6 months use. KTM ma pickup.',
                'price' => 28000,
                'price_type' => 'negotiable',
                'condition' => 'good',
                'brand' => 'FlexiSpot',
                'model' => 'E7',
                'specifications' => ['Size' => '140x70cm', 'Height Range' => '72-120cm', 'Material' => 'Bamboo', 'Motor' => 'Dual'],
            ],
            [
                'title' => 'GitHub Copilot - Free Access Guide',
                'description' => 'GitHub Copilot free access guide ra setup tutorial Nepali ma. Also includes VS Code extensions list productive coding ko lagi.',
                'price' => 0,
                'price_type' => 'free',
                'condition' => null,
                'specifications' => ['Format' => 'PDF + Video', 'Language' => 'Nepali / English', 'Includes' => 'VS Code Setup Guide'],
            ],
        ];

        foreach ($listings as $index => $listingData) {
            $user = $users->get($index % $users->count());
            $category = $categories->get($index % $categories->count());

            MarketplaceListing::create(array_merge($listingData, [
                'user_id' => $user->id,
                'slug' => \Illuminate\Support\Str::slug($listingData['title']) . '-' . ($index + 1),
                'category' => $category ? $category->name : 'General',
                'is_shippable' => fake()->boolean(20),
                'is_local_pickup' => true,
                'location' => fake()->randomElement(['Kathmandu', 'Lalitpur', 'Bhaktapur', 'Pokhara']),
                'status' => 'active',
                'views_count' => fake()->numberBetween(10, 500),
                'interested_count' => fake()->numberBetween(0, 20),
                'expires_at' => now()->addDays(30),
            ]));
        }
    }
}
