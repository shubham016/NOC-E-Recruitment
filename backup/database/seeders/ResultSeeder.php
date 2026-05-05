<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ResultSeeder extends Seeder
{
    public function run(): void
    {
        $results = [
            [
                'candidate_id' => 2, // ⚠️ CHANGE THIS to your actual candidate ID!
                'application_id' => null,
                'full_name' => 'shradha mainali',
                'citizenship_number' => '12345-67890',
                'roll_number' => 'NOC-2025-001',
                'advertisement_code' => 'ADV-2025-001',
                'advertisement_number' => '001/2025',
                'post' => 'Junior Engineer',
                'quota' => 'Open',
                'marks' => 85.50,
                'class' => 'First',
                'recommended_service' => 'Technical Service',
                'status' => 'published',
                'remarks' => null,
                'published_at' => Carbon::now()->subDays(2),
                'created_at' => Carbon::now()->subDays(30),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'candidate_id' => 2, // ⚠️ CHANGE THIS to your actual candidate ID!
                'application_id' => null,
                'full_name' => 'shradha mainali',
                'citizenship_number' => '12345-67890',
                'roll_number' => 'NOC-2025-002',
                'advertisement_code' => 'ADV-2024-015',
                'advertisement_number' => '015/2024',
                'post' => 'Assistant Officer',
                'quota' => 'Internal',
                'marks' => 72.25,
                'class' => 'Second',
                'recommended_service' => 'Administrative Service',
                'status' => 'published',
                'remarks' => null,
                'published_at' => Carbon::now()->subDays(5),
                'created_at' => Carbon::now()->subDays(45),
                'updated_at' => Carbon::now()->subDays(5),
            ],
            [
                'candidate_id' => 2, // ⚠️ CHANGE THIS to your actual candidate ID!
                'application_id' => null,
                'full_name' => 'shradha mainali',
                'citizenship_number' => '12345-67890',
                'roll_number' => 'NOC-2025-003',
                'advertisement_code' => 'ADV-2025-003',
                'advertisement_number' => '003/2025',
                'post' => 'Senior Accountant',
                'quota' => 'Open',
                'marks' => null,
                'class' => null,
                'recommended_service' => null,
                'status' => 'pending',
                'remarks' => 'Result under evaluation',
                'published_at' => null,
                'created_at' => Carbon::now()->subDays(10),
                'updated_at' => Carbon::now()->subDays(10),
            ],
        ];

        DB::table('results')->insert($results);

        $this->command->info('Results table seeded successfully!');
    }
}