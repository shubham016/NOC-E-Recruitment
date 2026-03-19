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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // candidate_id
            $table->string('user_type')->default('candidate'); // candidate, reviewer, admin
            $table->string('type'); // application_sent_back, application_approved, etc.
            $table->string('title');
            $table->text('message');
            $table->unsignedBigInteger('related_id')->nullable(); // application_id or job_id
            $table->string('related_type')->nullable(); // application, job, etc.
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'user_type', 'is_read']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
