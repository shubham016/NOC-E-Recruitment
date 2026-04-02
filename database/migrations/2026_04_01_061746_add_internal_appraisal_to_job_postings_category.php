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
        // Modify the category enum to include 'internal' and 'internal_appraisal'
        \DB::statement("ALTER TABLE `job_postings` MODIFY `category` ENUM('open', 'inclusive', 'internal', 'internal_appraisal') DEFAULT 'open'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        \DB::statement("ALTER TABLE `job_postings` MODIFY `category` ENUM('open', 'inclusive') DEFAULT 'open'");
    }
};
