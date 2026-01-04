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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys
            $table->unsignedBigInteger('candidate_id');
            $table->unsignedBigInteger('application_id')->nullable();
            
            // Candidate Information
            $table->string('full_name');
            $table->string('citizenship_number');
            $table->string('roll_number')->unique();
            
            // Advertisement Information
            $table->string('advertisement_code');
            $table->string('advertisement_number');
            $table->string('post');
            $table->string('quota')->nullable();
            
            // Result Information
            $table->decimal('marks', 5, 2)->nullable(); // e.g., 85.50
            $table->string('class')->nullable(); // First, Second, Third
            $table->string('recommended_service')->nullable();
            
            // Status
            $table->enum('status', ['pending', 'published', 'withheld'])->default('pending');
            $table->text('remarks')->nullable();
            
            // Publication Date
            $table->timestamp('published_at')->nullable();
            
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('candidate_id');
            $table->index('roll_number');
            $table->index('status');
            $table->index('advertisement_code');
            
            // Foreign key constraints (optional - uncomment if you have these tables)
            // $table->foreign('candidate_id')->references('id')->on('candidate_registration')->onDelete('cascade');
            // $table->foreign('application_id')->references('id')->on('application_form')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};