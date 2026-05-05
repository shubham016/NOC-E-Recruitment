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
            // Add unique index to notice_no column
            $table->unique('notice_no', 'job_postings_notice_no_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            // Drop unique index from notice_no column
            $table->dropUnique('job_postings_notice_no_unique');
        });
    }
};
