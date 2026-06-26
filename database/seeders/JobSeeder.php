<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        $jobs = [
            [
                'title' => 'Senior Laravel Developer',
                'description' => 'We are looking for an experienced Laravel developer to lead our backend team. You will architect solutions, mentor juniors, and work on high-traffic apps. Nepal-based, remote-friendly.',
                'type' => 'full_time',
                'location_type' => 'remote',
                'location' => null,
                'required_skills' => ['Laravel', 'PHP', 'MySQL', 'Redis', 'Vue.js', 'Git'],
                'company_name' => 'Deerwalk Services',
                'company_website' => 'https://deerwalk.com',
                'salary_min' => 80000,
                'salary_max' => 120000,
                'salary_currency' => 'NPR',
                'experience_level' => 'senior',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title' => 'React Frontend Developer',
                'description' => 'Join our frontend team building SaaS products for international clients. Modern stack with React 19, TypeScript, Tailwind. Work from Pulchowk office with flex hours.',
                'type' => 'full_time',
                'location_type' => 'hybrid',
                'location' => 'Pulchowk, Lalitpur',
                'required_skills' => ['React', 'TypeScript', 'Tailwind CSS', 'Next.js', 'GraphQL'],
                'company_name' => 'Leapfrog Technology',
                'company_website' => 'https://lftechnology.com',
                'salary_min' => 70000,
                'salary_max' => 110000,
                'salary_currency' => 'NPR',
                'experience_level' => 'mid',
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'title' => 'Full Stack Developer Intern',
                'description' => 'Great internship opportunity for recent graduates or final-year students. Work on Laravel + Vue.js projects with experienced mentors. Stipend and potential full-time offer.',
                'type' => 'internship',
                'location_type' => 'onsite',
                'location' => 'Baneshwor, Kathmandu',
                'required_skills' => ['PHP', 'JavaScript', 'HTML', 'CSS', 'Git'],
                'company_name' => 'YoungInnovations',
                'company_website' => 'https://younginnovations.com.np',
                'salary_min' => 15000,
                'salary_max' => 25000,
                'salary_currency' => 'NPR',
                'experience_level' => 'entry',
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'title' => 'DevOps Engineer',
                'description' => 'Manage cloud infrastructure on AWS/GCP, set up CI/CD pipelines with GitHub Actions, and ensure 99.9% uptime for our platforms. Docker and K8s experience a must.',
                'type' => 'full_time',
                'location_type' => 'remote',
                'location' => null,
                'required_skills' => ['Docker', 'Kubernetes', 'AWS', 'Terraform', 'Linux', 'CI/CD'],
                'company_name' => 'CloudFactory Nepal',
                'company_website' => 'https://cloudfactory.com',
                'salary_min' => 90000,
                'salary_max' => 150000,
                'salary_currency' => 'NPR',
                'experience_level' => 'senior',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'title' => 'Freelance Mobile App Developer',
                'description' => 'Looking for a contract mobile developer to build a cross-platform fitness tracking app. Must have experience with React Native or Flutter. 3-month project with possible extension.',
                'type' => 'freelance',
                'location_type' => 'remote',
                'location' => null,
                'required_skills' => ['React Native', 'TypeScript', 'Firebase', 'Node.js'],
                'company_name' => 'F1Soft International',
                'company_website' => 'https://f1soft.com',
                'salary_min' => 50000,
                'salary_max' => 80000,
                'salary_currency' => 'NPR',
                'experience_level' => 'mid',
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'title' => 'Python Backend Developer',
                'description' => 'Build and maintain Python microservices for our data processing pipeline. Django/FastAPI experience required. Work with a team of 5 on a US-based product.',
                'type' => 'full_time',
                'location_type' => 'onsite',
                'location' => 'Thapathali, Kathmandu',
                'required_skills' => ['Python', 'Django', 'PostgreSQL', 'Docker', 'REST APIs'],
                'company_name' => 'Docsumo',
                'company_website' => 'https://docsumo.com',
                'salary_min' => 85000,
                'salary_max' => 130000,
                'salary_currency' => 'NPR',
                'experience_level' => 'mid',
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'title' => 'Part-time Code Reviewer / Mentor',
                'description' => 'Review code submissions from students on our e-learning platform. Provide constructive feedback. 15-20 hrs/week, fully remote. Ideal for senior devs looking for side income.',
                'type' => 'part_time',
                'location_type' => 'remote',
                'location' => null,
                'required_skills' => ['PHP', 'JavaScript', 'Python', 'Code Review'],
                'company_name' => 'Broadway Infosys',
                'company_website' => 'https://broadwayinfosys.com',
                'salary_min' => 25000,
                'salary_max' => 40000,
                'salary_currency' => 'NPR',
                'experience_level' => 'senior',
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'title' => 'Lead Software Architect',
                'description' => 'Define technical vision for our fintech platform serving 500k+ users across Nepal. Must have experience with microservices, high-traffic systems, and team leadership.',
                'type' => 'full_time',
                'location_type' => 'hybrid',
                'location' => 'Durbar Marg, Kathmandu',
                'required_skills' => ['System Design', 'Microservices', 'Cloud Architecture', 'PHP', 'Go'],
                'company_name' => 'eSewa',
                'company_website' => 'https://esewa.com.np',
                'salary_min' => 150000,
                'salary_max' => 250000,
                'salary_currency' => 'NPR',
                'experience_level' => 'lead',
                'is_featured' => true,
                'is_active' => true,
            ],
        ];

        foreach ($jobs as $index => $jobData) {
            $user = $users->get($index % $users->count());

            Job::create(array_merge($jobData, [
                'user_id' => $user->id,
                'applications_count' => fake()->numberBetween(0, 50),
                'views_count' => fake()->numberBetween(50, 2000),
                'expires_at' => now()->addDays(fake()->numberBetween(10, 60)),
            ]));
        }
    }
}
