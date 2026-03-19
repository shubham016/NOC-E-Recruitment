<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For MySQL/MariaDB, we need to alter the ENUM column
        DB::statement("ALTER TABLE job_postings MODIFY COLUMN category ENUM('open', 'inclusive', 'internal') NOT NULL DEFAULT 'open'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original ENUM values
        DB::statement("ALTER TABLE job_postings MODIFY COLUMN category ENUM('open', 'inclusive') NOT NULL DEFAULT 'open'");
    }
};
