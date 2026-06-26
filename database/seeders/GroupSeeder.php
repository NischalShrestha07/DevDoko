<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\GroupEvent;
use App\Models\GroupPost;
use App\Models\GroupResource;
use App\Models\User;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    private static array $nepaliPosts = [
        'Namaste sabai lai! Aaja ma Laravel 12 ko naya feature "health check" herda ekdum ramro lagyo. Yo built-in health check le app ko status monitor garna sajilo banaidincha. Kasailai try gareko cha?',
        'Hamro team ma Git workflow ma dherai confusion cha. Main ra develop branch ko k k rules rakhne? Kasari conflict handle garne juniors lai?',
        'Aaja ko tip: Laravel Telescope Nepal ma hosted apps ko lagi debugging ko best tool ho. Queue ma failed job cha bhane Telescope ma directly herna milcha.',
        'Hey guys! Maile "Khalti" payment integration package banaune soch ma chu. Kasailai interest cha contribute garna? Open source project hola yo.',
        'React 19 ma aako nayi "use" hook hernu na. API data fetching kati sajilo bho. Aba useEffect + useState ko jhyau chaincha.',
        'Nepal ma developer ko lagi sabse ramro YouTube channels: 1) Khemraj Pudasaini bhai (Nepali ma) 2) Laracasts 3) Theo (t3.gg). Aru suggestions?',
    ];

    private static array $nepaliResources = [
        ['title' => 'Laravel Deployment on Nepal Hosting (WebHost)', 'description' => 'Step-by-step guide to deploy Laravel apps on Nepali hosting providers like WebHost Nepal and CloudTech.', 'type' => 'tutorial'],
        ['title' => 'Nepali Date Converter Package', 'description' => 'Composer package for BS/AD date conversion. Supports all Nepali festivals and public holidays.', 'type' => 'tool'],
        ['title' => 'How to Get Remote Jobs from Nepali Companies', 'description' => 'List of top Nepali IT companies hiring remote. Includes salary ranges and application tips.', 'type' => 'link'],
        ['title' => 'Docker for Nepali Devs - Free Ebook', 'description' => 'Beginner-friendly Docker guide written specifically for the Nepali developer context. Available in Nepali and English.', 'type' => 'book'],
    ];

    public function run(): void
    {
        $users = User::all();

        $groups = [
            [
                'name' => 'Laravel Developers Nepal',
                'description' => 'Nepali Laravel developers ko lagi community. Sasrika Lag! Setup, deployment, packages, jobs sabai discuss garaula.',
                'category' => 'tech-stack',
                'tags' => ['Laravel', 'PHP', 'Nepal'],
                'privacy' => 'public',
                'member_approval' => 'anyone',
            ],
            [
                'name' => 'React Enthusiasts Nepal',
                'description' => 'React, Next.js, Remix — frontend ecosystem ko latest discuss garna join gara. Beginners lai pani swagat cha.',
                'category' => 'tech-stack',
                'tags' => ['React', 'JavaScript', 'Frontend'],
                'privacy' => 'public',
                'member_approval' => 'anyone',
            ],
            [
                'name' => 'DevOps & Cloud Nepal',
                'description' => 'CI/CD, Docker, Kubernetes, AWS/GCP — cloud infrastructure bare kura garna yeta join gara. Nepali cloud engineers ko community.',
                'category' => 'interest',
                'tags' => ['DevOps', 'Docker', 'Kubernetes', 'AWS'],
                'privacy' => 'public',
                'member_approval' => 'anyone',
            ],
            [
                'name' => 'Open Source Nepal',
                'description' => 'Nepali developers harule contribute gareko open source projects. Collaboration, mentorship, first PR sabai help pauchau.',
                'category' => 'project',
                'tags' => ['Open Source', 'GitHub', 'Collaboration'],
                'privacy' => 'public',
                'member_approval' => 'admin_approval',
            ],
            [
                'name' => 'Python & Data Science Nepal',
                'description' => 'Python, ML, AI, Data Science ma interest cha bhane join gara. Nepal ko context ma data projects discuss garaula.',
                'category' => 'learning',
                'tags' => ['Python', 'Data Science', 'Machine Learning'],
                'privacy' => 'public',
                'member_approval' => 'anyone',
            ],
            [
                'name' => 'Kathmandu Developers Meetup',
                'description' => 'Kathmandu valley ma basne developers ko lagi monthly meetup, hackathon ra tech talks organize garne group.',
                'category' => 'location',
                'tags' => ['Kathmandu', 'Nepal', 'Meetup'],
                'privacy' => 'public',
                'member_approval' => 'anyone',
            ],
        ];

        foreach ($groups as $index => $groupData) {
            $owner = $users->get($index % $users->count());

            $group = Group::create(array_merge($groupData, [
                'owner_id' => $owner->id,
                'slug' => \Illuminate\Support\Str::slug($groupData['name']),
            ]));

            $memberCount = rand(2, 4);
            $members = $users->where('id', '!=', $owner->id)
                ->random(min($memberCount, $users->count() - 1));

            foreach ($members as $member) {
                $group->members()->attach($member->id, [
                    'role' => $memberCount > 2 && $members->search($member) === 0 ? 'admin' : 'member',
                    'status' => 'active',
                    'joined_at' => now()->subDays(rand(1, 30)),
                    'approved_at' => now()->subDays(rand(1, 30)),
                    'approved_by' => $owner->id,
                    'settings' => json_encode(['notifications' => 'all']),
                ]);
                $group->increment('members_count');
            }

            $allMembers = collect([$owner])->concat($members);
            $postCount = rand(1, 3);

            for ($i = 0; $i < $postCount; $i++) {
                $postAuthor = $allMembers->random();
                $postTypes = ['general', 'announcement', 'question', 'resource'];

                $postContent = self::$nepaliPosts[array_rand(self::$nepaliPosts)];
                GroupPost::create([
                    'group_id' => $group->id,
                    'user_id' => $postAuthor->id,
                    'title' => \Illuminate\Support\Str::limit($postContent, 80, ''),
                    'content' => $postContent,
                    'type' => $postTypes[array_rand($postTypes)],
                    'is_pinned' => $i === 0 && rand(0, 1),
                ]);

                $group->increment('posts_count');
            }

            $resourceCount = rand(1, 2);
            for ($i = 0; $i < $resourceCount; $i++) {
                $resourceAuthor = $allMembers->random();
                $resource = self::$nepaliResources[array_rand(self::$nepaliResources)];

                GroupResource::create([
                    'group_id' => $group->id,
                    'user_id' => $resourceAuthor->id,
                    'title' => $resource['title'],
                    'description' => $resource['description'],
                    'type' => $resource['type'],
                    'url' => 'https://github.com/' . strtolower(str_replace(' ', '-', $resource['title'])),
                    'tags' => fake()->randomElements(['Laravel', 'PHP', 'React', 'Python', 'Docker', 'JavaScript', 'API'], rand(1, 3)),
                ]);
            }

            if ($index % 2 === 0) {
                $eventAuthor = $allMembers->random();
                $eventFormats = ['online', 'in_person', 'hybrid'];

                GroupEvent::create([
                    'group_id' => $group->id,
                    'user_id' => $eventAuthor->id,
                    'title' => fake()->randomElement([
                        'Laravel Zero to Hero Workshop',
                        'React 19 New Features Webinar',
                        'Open Source Contribution Hackathon',
                        'DevOps with Docker Meetup',
                    ]),
                    'description' => fake()->paragraphs(2, true),
                    'type' => fake()->randomElement(['meetup', 'workshop', 'webinar', 'hackathon', 'social']),
                    'format' => $eventFormats[array_rand($eventFormats)],
                    'location' => 'Kathmandu, Nepal',
                    'meeting_link' => 'https://meet.google.com/' . fake()->word(),
                    'starts_at' => now()->addDays(rand(5, 30)),
                    'ends_at' => now()->addDays(rand(5, 30))->addHours(rand(1, 4)),
                    'max_attendees' => rand(20, 100),
                ]);
            }

            $group->update(['last_active_at' => now()->subHours(rand(1, 72))]);
        }
    }
}
