<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_posting_id')->constrained('job_postings')->onDelete('cascade');
            $table->unsignedBigInteger('reviewer_id')->nullable();
            $table->foreignId('candidate_id')->constrained('candidates')->onDelete('cascade');
            $table->foreignId('reviewed_by')->nullable()->constrained('reviewers')->onDelete('set null');
            $table->enum('status', ['pending', 'under_review', 'shortlisted', 'rejected'])->default('pending');
            $table->text('cover_letter');
            $table->string('resume_path')->nullable();
            $table->json('additional_documents')->nullable();
            $table->text('reviewer_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};