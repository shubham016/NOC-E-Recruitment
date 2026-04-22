<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Drop the unique constraint on notice_no so multiple advertisements
     * can be published under the same Notice No.
     */
    public function up(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropUnique('job_postings_notice_no_unique');
        });
    }

    /**
     * Restore the unique constraint.
     */
    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->unique('notice_no', 'job_postings_notice_no_unique');
        });
    }
};
