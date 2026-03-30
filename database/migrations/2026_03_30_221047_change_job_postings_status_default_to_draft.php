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
        Schema::table('job_postings', function (Blueprint $table) {
            // Change status default from 'active' to 'draft'
            $table->enum('status', ['draft', 'active', 'closed'])->default('draft')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            // Revert status default back to 'active'
            $table->enum('status', ['draft', 'active', 'closed'])->default('active')->change();
        });
    }
};
