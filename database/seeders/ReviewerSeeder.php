<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reviewer;

class ReviewerSeeder extends Seeder
{
    public function run()
    {
        Reviewer::updateOrCreate(
            ['employee_id' => 'REV001'],
            [
                'name' => 'Admin Reviewer',
                'email' => 'reviewer@example.com',
                'password' => 'password123',
                'phone' => '9800000000',
                'department' => 'HR',
                'designation' => null,
                'status' => 'active',
            ]
        );

        Reviewer::updateOrCreate(
            ['employee_id' => 'REV002'],
            [
                'name' => 'Second Reviewer',
                'email' => 'reviewer2@example.com',
                'password' => 'password123',
                'phone' => '9811111111',
                'department' => 'IT',
                'designation' => null,
                'status' => 'active',
            ]
        );
    }
}
