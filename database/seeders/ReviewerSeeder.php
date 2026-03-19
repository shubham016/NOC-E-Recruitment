<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reviewer;

class ReviewerSeeder extends Seeder
{
    public function run()
    {
        Reviewer::create([
            'name' => 'Admin Reviewer',
            'email' => 'reviewer@example.com',
            'password' => 'password123',
            'phone' => '9800000000',
            'department' => 'HR',
            'status' => 'active',
        ]);

        Reviewer::create([
            'name' => 'Second Reviewer',
            'email' => 'reviewer2@example.com',
            'password' => 'password123',
            'phone' => '9811111111',
            'department' => 'IT',
            'status' => 'active',
        ]);
    }
}