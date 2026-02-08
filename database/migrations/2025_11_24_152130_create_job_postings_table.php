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

            // Salary fields (optional)
            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();

            // Deadline and Status
            $table->date('deadline');
            $table->enum('status', ['draft', 'active', 'closed'])->default('active');

            // Posted by fields
            $table->unsignedBigInteger('posted_by');
            $table->string('posted_by_type')->default('admin');

            $table->timestamps();

            // Add indexes for better performance
            $table->index(['status', 'deadline']);
            $table->index(['posted_by', 'posted_by_type']);
            $table->index('category');
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