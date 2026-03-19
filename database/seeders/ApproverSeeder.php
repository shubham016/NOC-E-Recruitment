<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Approver;
use Illuminate\Support\Facades\Hash;

class ApproverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Approver::create([
            'employee_id' => 'APR001',
            'name' => 'Test Approver',
            'phone_number' => '9800000001',
            'email' => 'approver@noc.gov.np',
            'designation' => 'Senior Approver',
            'department' => 'Human Resources',
            'job_posting_id' => null, // Can approve applications for all jobs
            'photo' => null,
            'status' => 'active',
            'password' => Hash::make('password123'),
        ]);

        Approver::create([
            'employee_id' => 'APR002',
            'name' => 'Department Approver',
            'phone_number' => '9800000002',
            'email' => 'dept.approver@noc.gov.np',
            'designation' => 'Department Head',
            'department' => 'Administration',
            'job_posting_id' => 1, // Assigned to specific job posting
            'photo' => null,
            'status' => 'active',
            'password' => Hash::make('password123'),
        ]);
    }
}
