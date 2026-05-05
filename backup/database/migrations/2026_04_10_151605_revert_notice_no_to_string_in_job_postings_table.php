<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Revert back to VARCHAR(50) to support formats like "36/2082-83"
        DB::statement("ALTER TABLE job_postings MODIFY COLUMN notice_no VARCHAR(50) NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE job_postings MODIFY COLUMN notice_no INT UNSIGNED NULL");
    }
};
