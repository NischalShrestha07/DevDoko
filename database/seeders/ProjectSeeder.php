<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        $projects = [
            [
                'title' => 'Khalti Payment Integration Package',
                'short_description' => 'Laravel package for Khalti payment gateway integration',
                'description' => 'A complete Laravel package for integrating Khalti payment gateway in Nepali applications. Supports both E-payment and Merchant SDK, with easy configuration, webhook handling, and transaction logging.',
                'repository_url' => 'https://github.com/anupghimire/khalti-laravel',
                'technologies' => ['Laravel', 'PHP', 'Khalti API'],
                'category' => 'package',
                'difficulty' => 'intermediate',
                'status' => 'active',
            ],
            [
                'title' => 'Hamro Patro - Nepali Calendar API',
                'short_description' => 'RESTful API for Nepali date conversion and calendar events',
                'description' => 'A Laravel-based API that converts dates between Bikram Sambat (BS) and Gregorian (AD). Includes public holidays, festivals tithi, and astrological events. Used by multiple Nepali apps.',
                'repository_url' => 'https://github.com/roshanadhikari/nepali-calendar-api',
                'technologies' => ['Laravel', 'PostgreSQL', 'Redis'],
                'category' => 'api',
                'difficulty' => 'intermediate',
                'status' => 'active',
            ],
            [
                'title' => 'Employee Attendance System',
                'short_description' => 'Biometric attendance system with real-time tracking',
                'description' => 'A web-based employee attendance management system built for Nepali companies. Features include facial recognition check-in, geo-fencing for location tracking, leave management, and payroll integration.',
                'technologies' => ['React', 'Python', 'Flask', 'MySQL', 'Docker'],
                'category' => 'web-application',
                'difficulty' => 'advanced',
                'status' => 'active',
                'live_url' => 'https://attendance.demo.com.np',
            ],
            [
                'title' => 'Food Delivery App Backend',
                'short_description' => 'Scalable delivery platform backend for restaurants in Nepal',
                'description' => 'Backend for a food delivery platform supporting real-time order tracking, Push notifications via FCM, SMS alerts via Spatie, and integrated with Khalti/esewa payment. Optimized for Kathmandu delivery logistics.',
                'repository_url' => 'https://github.com/sagarpoudel/food-delivery-api',
                'technologies' => ['Node.js', 'Express', 'MongoDB', 'Redis', 'WebSockets'],
                'category' => 'api',
                'difficulty' => 'advanced',
                'status' => 'active',
            ],
            [
                'title' => 'Nepali Typing Practice App',
                'short_description' => 'Premshikha-style Nepali typing tutor built with React',
                'description' => 'A browser-based typing tutor for Nepali Unicode (Romanized and Traditional). Features real-time WPM tracking, accuracy analysis, and daily challenges. Supports both Preeti and Unicode fonts.',
                'live_url' => 'https://nepali-type.example.com',
                'technologies' => ['React', 'TypeScript', 'Tailwind CSS'],
                'category' => 'web-application',
                'difficulty' => 'beginner',
                'status' => 'completed',
            ],
            [
                'title' => 'Developer Job Board Nepal',
                'short_description' => 'Job listing platform specifically for Nepali developers',
                'description' => 'A niche job board connecting Nepali developers with local and remote opportunities. Features skill-based matching, salary insights, company reviews, and direct messaging.',
                'repository_url' => 'https://github.com/bibekgurung/devjobs-nepal',
                'technologies' => ['Laravel', 'Vue.js', 'Tailwind CSS', 'MySQL'],
                'category' => 'web-application',
                'difficulty' => 'intermediate',
                'status' => 'active',
            ],
            [
                'title' => 'KIST College Management System',
                'short_description' => 'College management with student portal, fees, exams',
                'description' => 'A comprehensive college management system built for KIST College. Manages student admissions, fee tracking, exam schedules, grade sheets, and parent communication portal.',
                'technologies' => ['Laravel', 'Livewire', 'MySQL', 'Bootstrap'],
                'category' => 'web-application',
                'difficulty' => 'advanced',
                'status' => 'completed',
            ],
            [
                'title' => 'WhatsApp Bot for Business',
                'short_description' => 'Automated WhatsApp business assistant using Twilio',
                'description' => 'A WhatsApp business bot built with Node.js and Twilio API. Handles customer queries, order confirmations, appointment booking, and automated replies in both Nepali and English.',
                'repository_url' => 'https://github.com/priyathapa/whatsapp-bot-np',
                'technologies' => ['Node.js', 'Twilio', 'MongoDB', 'Redis'],
                'category' => 'tool',
                'difficulty' => 'intermediate',
                'status' => 'active',
            ],
            [
                'title' => 'Bus Ticketing System - Nepal Routes',
                'short_description' => 'Online bus ticket booking for inter-city routes in Nepal',
                'description' => 'An online platform for booking bus tickets across major routes: KTM-PKR, KTM-CHT, KTM-BRT. Features live seat availability, e-ticket generation, SMS confirmation, and QR code check-in.',
                'technologies' => ['Laravel', 'Vue.js', 'MySQL', 'Redis'],
                'category' => 'web-application',
                'difficulty' => 'advanced',
                'status' => 'planning',
            ],
            [
                'title' => 'Eso Service - Home Services Platform',
                'short_description' => 'On-demand home services booking platform for Kathmandu',
                'description' => 'A marketplace for home services (plumber, electrician, cleaning, tutoring) in Kathmandu Valley. Features real-time booking, service provider verification, pricing calculator, and customer reviews.',
                'technologies' => ['Laravel', 'React', 'MySQL', 'WebSockets'],
                'category' => 'mobile',
                'difficulty' => 'expert',
                'status' => 'active',
            ],
        ];

        foreach ($projects as $index => $projectData) {
            $user = $users->get($index % $users->count());

            Project::create(array_merge($projectData, [
                'user_id' => $user->id,
                'is_public' => true,
                'is_featured' => $index < 3,
                'views_count' => fake()->numberBetween(20, 1200),
                'forks_count' => fake()->numberBetween(0, 30),
                'likes_count' => fake()->numberBetween(1, 80),
                'screenshots' => null,
            ]));
        }
    }
}
