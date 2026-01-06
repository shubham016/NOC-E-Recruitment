<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            
            // Government-specific fields
            $table->string('advertisement_no')->unique();
            $table->string('title');
            $table->string('position_level');
            $table->text('description');
            $table->text('requirements');
            $table->text('minimum_qualification');
            
            // Department and Service
            $table->string('department')->default('Government Department');
            $table->string('service_group');
            
            // Category fields
            $table->enum('category', ['open', 'inclusive'])->default('open');
            $table->string('inclusive_type')->nullable();
            $table->integer('number_of_posts')->default(1);
            
            // Location and Type
            $table->string('location')->default('Nepal');
            $table->string('job_type')->default('permanent');
                        
            // Deadline and Status
            $table->date('deadline');
            $table->enum('status', ['draft', 'active', 'closed'])->default('active');
            
            // Posted by admin
            $table->foreignId('posted_by')->constrained('admins')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};