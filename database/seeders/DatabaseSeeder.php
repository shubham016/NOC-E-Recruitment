<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Reviewer;
use App\Models\Candidate;
use App\Models\Job;
use App\Models\Application;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin
        $admin = Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@recruitment.com',
            'password' => Hash::make('password'),
            'phone' => '1234567890',
            'status' => 'active',
        ]);

        // Create Reviewer
        $reviewer = Reviewer::create([
            'name' => 'John Reviewer',
            'email' => 'reviewer@recruitment.com',
            'password' => Hash::make('password'),
            'phone' => '9876543210',
            'department' => 'HR Department',
            'status' => 'active',
        ]);

        // Create Test Candidates
        $candidate1 = Candidate::create([
            'name' => 'Jane Candidate',
            'email' => 'candidate@recruitment.com',
            'password' => Hash::make('password'),
            'phone' => '5555555555',
            'address' => '123 Test Street, New York, NY',
            'status' => 'active',
        ]);

        $candidate2 = Candidate::create([
            'name' => 'Jessica Smith',
            'email' => 'jessica.smith@email.com',
            'password' => Hash::make('password'),
            'phone' => '5551234567',
            'address' => '456 Demo Ave, San Francisco, CA',
            'status' => 'active',
        ]);

        $candidate3 = Candidate::create([
            'name' => 'Michael Brown',
            'email' => 'michael.brown@email.com',
            'password' => Hash::make('password'),
            'phone' => '5559876543',
            'address' => '789 Sample Rd, Austin, TX',
            'status' => 'active',
        ]);

        $candidate4 = Candidate::create([
            'name' => 'Emily Davis',
            'email' => 'emily.davis@email.com',
            'password' => Hash::make('password'),
            'phone' => '5556543210',
            'address' => '321 Example Ln, Seattle, WA',
            'status' => 'active',
        ]);

        // Create Jobs
        $job1 = Job::create([
            'title' => 'Senior Frontend Developer',
            'description' => 'We are looking for an experienced Frontend Developer to join our team.',
            'department' => 'Engineering',
            'location' => 'Remote',
            'job_type' => 'full-time',
            'salary_min' => 80000,
            'salary_max' => 120000,
            'requirements' => '5+ years of experience in React, Vue.js, and modern JavaScript',
            'deadline' => now()->addDays(30),
            'status' => 'open',
            'created_by' => $admin->id,
        ]);

        $job2 = Job::create([
            'title' => 'UX/UI Designer',
            'description' => 'Join our design team to create amazing user experiences.',
            'department' => 'Design',
            'location' => 'Hybrid',
            'job_type' => 'full-time',
            'salary_min' => 70000,
            'salary_max' => 100000,
            'requirements' => '3+ years of experience in UI/UX design, Figma, Adobe XD',
            'deadline' => now()->addDays(25),
            'status' => 'open',
            'created_by' => $admin->id,
        ]);

        $job3 = Job::create([
            'title' => 'Product Manager',
            'description' => 'Lead product development and strategy.',
            'department' => 'Product',
            'location' => 'On-site',
            'job_type' => 'full-time',
            'salary_min' => 100000,
            'salary_max' => 150000,
            'requirements' => '7+ years of product management experience',
            'deadline' => now()->addDays(35),
            'status' => 'open',
            'created_by' => $admin->id,
        ]);

        $job4 = Job::create([
            'title' => 'DevOps Engineer',
            'description' => 'Manage our cloud infrastructure and deployment pipelines.',
            'department' => 'Engineering',
            'location' => 'Remote',
            'job_type' => 'full-time',
            'salary_min' => 90000,
            'salary_max' => 130000,
            'requirements' => '4+ years of DevOps experience, AWS, Docker, Kubernetes',
            'deadline' => now()->addDays(28),
            'status' => 'open',
            'created_by' => $admin->id,
        ]);

        // Create Applications
        Application::create([
            'job_posting_id' => $job1->id, // Changed from job_id
            'candidate_id' => $candidate2->id,
            'cover_letter' => 'I am very interested in the Senior Frontend Developer position. With over 5 years of experience in React and Vue.js, I believe I would be a great fit for your team.',
            'status' => 'pending',
        ]);

        Application::create([
            'job_posting_id' => $job2->id, // Changed from job_id
            'candidate_id' => $candidate3->id,
            'cover_letter' => 'I would love to join your design team. I have extensive experience with Figma and creating user-centered designs.',
            'status' => 'pending',
        ]);

        Application::create([
            'job_posting_id' => $job3->id, // Changed from job_id
            'candidate_id' => $candidate4->id,
            'cover_letter' => 'With my 7+ years of product management experience, I am confident I can drive your product strategy forward.',
            'status' => 'pending',
        ]);

        Application::create([
            'job_posting_id' => $job4->id, // Changed from job_id
            'candidate_id' => $candidate1->id,
            'cover_letter' => 'I am excited to apply for the DevOps Engineer position. I have 4+ years of experience with AWS, Docker, and Kubernetes.',
            'status' => 'pending',
        ]);

        // Create some reviewed applications
        Application::create([
            'job_posting_id' => $job1->id, // Changed from job_id
            'candidate_id' => $candidate1->id,
            'cover_letter' => 'I am a skilled frontend developer with React expertise.',
            'status' => 'shortlisted',
            'reviewed_by' => $reviewer->id,
            'reviewer_notes' => 'Great candidate with excellent experience. Moving forward to interview stage.',
            'reviewed_at' => now()->subHours(2),
        ]);
    }
}