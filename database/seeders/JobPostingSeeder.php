<?php

namespace Database\Seeders;

use App\Models\JobPosting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobPostingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobPostings = [
            [
                'advertisement_no' => 'ADV-2025-001',
                'title' => 'Section Officer',
                'position_level' => 'Officer Level - 5th',
                'description' => 'We are seeking qualified candidates for the position of Section Officer in the Ministry of Finance. The selected candidate will be responsible for administrative duties, policy implementation, and coordination with various departments.',
                'requirements' => 'Strong analytical and problem-solving skills, excellent communication abilities, proficiency in government procedures and regulations, ability to work under pressure and meet deadlines.',
                'minimum_qualification' => 'Bachelor\'s degree in Management, Public Administration, or related field from a recognized university. Minimum 2 years of experience in government or related sector preferred.',
                'department' => 'Ministry of Finance',
                'service_group' => 'Administration Service',
                'category' => 'open',
                'inclusive_type' => null,
                'number_of_posts' => 3,
                'location' => 'Kathmandu, Nepal',
                'job_type' => 'permanent',
                'deadline' => now()->addMonths(2)->format('Y-m-d'),
                'status' => 'active',
                'posted_by' => 1,
            ],
            [
                'advertisement_no' => 'ADV-2025-002',
                'title' => 'Junior Engineer',
                'position_level' => 'Officer Level - 4th',
                'description' => 'The Department of Roads is looking for Junior Engineers to work on infrastructure development projects across Nepal. Responsibilities include site supervision, technical report preparation, and quality assurance.',
                'requirements' => 'Knowledge of civil engineering principles, AutoCAD and engineering software proficiency, field experience in construction projects, valid engineering license.',
                'minimum_qualification' => 'Bachelor\'s degree in Civil Engineering from Nepal Engineering Council recognized institution. Fresh graduates are encouraged to apply.',
                'department' => 'Department of Roads',
                'service_group' => 'Engineering Service',
                'category' => 'inclusive',
                'inclusive_type' => 'women',
                'number_of_posts' => 5,
                'location' => 'Various Districts, Nepal',
                'job_type' => 'permanent',
                'deadline' => now()->addDays(45)->format('Y-m-d'),
                'status' => 'active',
                'posted_by' => 1,
            ],
            [
                'advertisement_no' => 'ADV-2025-003',
                'title' => 'Health Assistant',
                'position_level' => 'Assistant Level - 3rd',
                'description' => 'Ministry of Health and Population requires Health Assistants for various health posts in rural areas. The role involves providing primary healthcare services, health education, and supporting community health programs.',
                'requirements' => 'Basic medical knowledge, patient care skills, ability to work in remote areas, familiarity with government health protocols, commitment to public service.',
                'minimum_qualification' => 'Health Assistant certificate from CTEVT or equivalent institution. Registration with Nepal Health Professional Council is mandatory.',
                'department' => 'Ministry of Health and Population',
                'service_group' => 'Health Service',
                'category' => 'inclusive',
                'inclusive_type' => 'indigenous',
                'number_of_posts' => 10,
                'location' => 'Rural Districts, Nepal',
                'job_type' => 'permanent',
                'deadline' => now()->addMonths(1)->format('Y-m-d'),
                'status' => 'active',
                'posted_by' => 1,
            ],
            [
                'advertisement_no' => 'ADV-2025-004',
                'title' => 'Assistant Teacher',
                'position_level' => 'Teacher Level - 2nd',
                'description' => 'The Ministry of Education is recruiting Assistant Teachers for government schools. Selected candidates will be responsible for teaching, curriculum development, and student assessment.',
                'requirements' => 'Teaching skills, classroom management abilities, knowledge of modern teaching methodologies, proficiency in Nepali and English languages.',
                'minimum_qualification' => 'Bachelor\'s degree in Education (B.Ed) or Bachelor\'s degree with one year teaching training. Teaching Service Commission license required.',
                'department' => 'Ministry of Education, Science and Technology',
                'service_group' => 'Education Service',
                'category' => 'open',
                'inclusive_type' => null,
                'number_of_posts' => 15,
                'location' => 'All Seven Provinces, Nepal',
                'job_type' => 'permanent',
                'deadline' => now()->addMonths(3)->format('Y-m-d'),
                'status' => 'active',
                'posted_by' => 1,
            ],
            [
                'advertisement_no' => 'ADV-2025-005',
                'title' => 'IT Officer',
                'position_level' => 'Officer Level - 6th',
                'description' => 'The Department of Information Technology requires IT Officers for managing government IT infrastructure, developing e-governance solutions, and providing technical support.',
                'requirements' => 'Programming skills (PHP, Python, Java), database management, network administration, cybersecurity knowledge, problem-solving abilities.',
                'minimum_qualification' => 'Bachelor\'s degree in Computer Science, IT, or related field. Relevant certifications are an advantage.',
                'department' => 'Department of Information Technology',
                'service_group' => 'Technical Service',
                'category' => 'open',
                'inclusive_type' => null,
                'number_of_posts' => 2,
                'location' => 'Kathmandu, Nepal',
                'job_type' => 'permanent',
                'deadline' => now()->addDays(60)->format('Y-m-d'),
                'status' => 'active',
                'posted_by' => 1,
            ],
        ];

        foreach ($jobPostings as $posting) {
            JobPosting::create($posting);
        }
    }
}