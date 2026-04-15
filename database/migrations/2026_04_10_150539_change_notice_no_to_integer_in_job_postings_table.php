<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Clear any non-numeric values (e.g. "12/2082-83") so column change succeeds
        DB::statement("UPDATE job_postings SET notice_no = NULL WHERE notice_no IS NOT NULL AND notice_no NOT REGEXP '^[0-9]+$'");

        // Change from VARCHAR(50) to UNSIGNED INT (nullable)
        DB::statement("ALTER TABLE job_postings MODIFY COLUMN notice_no INT UNSIGNED NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE job_postings MODIFY COLUMN notice_no VARCHAR(50) NULL");
    }
};
