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
            $table->string('notice_no', 50)->nullable()->after('id');
            $table->index('notice_no', 'idx_job_postings_notice_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropIndex('idx_job_postings_notice_no');
            $table->dropColumn('notice_no');
        });
    }
};
