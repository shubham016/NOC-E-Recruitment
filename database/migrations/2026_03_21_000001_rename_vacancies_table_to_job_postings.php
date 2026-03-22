<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('vacancies') && !Schema::hasTable('job_postings')) {
            Schema::rename('vacancies', 'job_postings');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('job_postings') && !Schema::hasTable('vacancies')) {
            Schema::rename('job_postings', 'vacancies');
        }
    }
};
