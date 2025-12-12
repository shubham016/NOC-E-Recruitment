<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            
            // Name fields
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('name')->nullable(); // Auto-generated full name
            
            // Contact fields
            $table->string('mobile_number', 10)->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            
            // Authentication fields
            $table->string('username')->unique();
            $table->string('password');
            
            // // Optional profile fields (for future use)
            // $table->string('profile_picture')->nullable();
            // $table->string('city')->nullable();
            // $table->string('state')->nullable();
            // $table->string('country')->nullable();
            // $table->string('qualification')->nullable();
            // $table->text('skills')->nullable();
            // $table->string('resume_path')->nullable();
            
            // Status
            $table->enum('status', ['active', 'inactive'])->default('active');
            
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidates');
    }
};