<?php
// database/seeders/MarketplaceCategorySeeder.php

namespace Database\Seeders;

use App\Models\MarketplaceCategory;
use Illuminate\Database\Seeder;

class MarketplaceCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Hardware',
                'icon' => 'bi-laptop',
                'description' => 'Computers, laptops, servers, and hardware components',
                'children' => [
                    ['name' => 'Laptops', 'icon' => 'bi-laptop'],
                    ['name' => 'Desktop Computers', 'icon' => 'bi-pc'],
                    ['name' => 'Monitors', 'icon' => 'bi-display'],
                    ['name' => 'Keyboards & Mice', 'icon' => 'bi-keyboard'],
                    ['name' => 'Servers', 'icon' => 'bi-hdd-stack'],
                    ['name' => 'Networking Equipment', 'icon' => 'bi-wifi'],
                    ['name' => 'Storage Devices', 'icon' => 'bi-hdd'],
                    ['name' => 'Components (CPU, GPU, RAM)', 'icon' => 'bi-cpu'],
                ],
            ],
            [
                'name' => 'Software & Licenses',
                'icon' => 'bi-code-square',
                'description' => 'Software licenses, development tools, and subscriptions',
                'children' => [
                    ['name' => 'IDEs & Editors', 'icon' => 'bi-code-slash'],
                    ['name' => 'Design Software', 'icon' => 'bi-brush'],
                    ['name' => 'Development Tools', 'icon' => 'bi-tools'],
                    ['name' => 'Operating Systems', 'icon' => 'bi-windows'],
                    ['name' => 'Database Software', 'icon' => 'bi-database'],
                    ['name' => 'Cloud Subscriptions', 'icon' => 'bi-cloud'],
                ],
            ],
            [
                'name' => 'Books & Courses',
                'icon' => 'bi-book',
                'description' => 'Programming books, courses, and learning materials',
                'children' => [
                    ['name' => 'Programming Books', 'icon' => 'bi-book'],
                    ['name' => 'Video Courses', 'icon' => 'bi-play-circle'],
                    ['name' => 'Tutorials', 'icon' => 'bi-file-text'],
                    ['name' => 'Documentation', 'icon' => 'bi-files'],
                ],
            ],
            [
                'name' => 'Developer Accessories',
                'icon' => 'bi-headphones',
                'description' => 'Desk accessories, ergonomic gear, and developer swag',
                'children' => [
                    ['name' => 'Desk Accessories', 'icon' => 'bi-table'],
                    ['name' => 'Ergonomic Gear', 'icon' => 'bi-person-workspace'],
                    ['name' => 'Developer Merch', 'icon' => 'bi-hoodie'],
                    ['name' => 'Stickers & Decals', 'icon' => 'bi-sticky'],
                ],
            ],
            [
                'name' => 'Services',
                'icon' => 'bi-briefcase',
                'description' => 'Freelance services, consulting, and development work',
                'children' => [
                    ['name' => 'Web Development', 'icon' => 'bi-globe'],
                    ['name' => 'Mobile Development', 'icon' => 'bi-phone'],
                    ['name' => 'UI/UX Design', 'icon' => 'bi-palette'],
                    ['name' => 'Consulting', 'icon' => 'bi-chat'],
                    ['name' => 'Code Reviews', 'icon' => 'bi-search'],
                    ['name' => 'Mentoring', 'icon' => 'bi-people'],
                ],
            ],
            [
                'name' => 'Gaming',
                'icon' => 'bi-controller',
                'description' => 'Gaming gear, consoles, and game development tools',
                'children' => [
                    ['name' => 'Gaming PCs', 'icon' => 'bi-pc-display'],
                    ['name' => 'Consoles', 'icon' => 'bi-controller'],
                    ['name' => 'Gaming Accessories', 'icon' => 'bi-headset'],
                    ['name' => 'Game Dev Tools', 'icon' => 'bi-unity'],
                ],
            ],
        ];

        foreach ($categories as $categoryData) {
            $children = $categoryData['children'] ?? [];
            unset($categoryData['children']);

            $parent = MarketplaceCategory::create($categoryData);

            foreach ($children as $index => $childData) {
                $childData['parent_id'] = $parent->id;
                $childData['order'] = $index;
                MarketplaceCategory::create($childData);
            }
        }
    }
}
