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
            // Add has_internal field first if it doesn't exist
            if (!Schema::hasColumn('job_postings', 'has_internal')) {
                $table->boolean('has_internal')->default(false);
            }

            // Add internal sub-category fields
            $table->boolean('has_internal_open')->default(false);
            $table->boolean('has_internal_inclusive')->default(false);
            $table->text('internal_inclusive_types')->nullable()->comment('JSON array of inclusive types for Internal category');

            // Add indexes
            $table->index('has_internal', 'idx_job_postings_has_internal');
            $table->index('has_internal_open', 'idx_job_postings_has_internal_open');
            $table->index('has_internal_inclusive', 'idx_job_postings_has_internal_inclusive');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropIndex('idx_job_postings_has_internal');
            $table->dropIndex('idx_job_postings_has_internal_open');
            $table->dropIndex('idx_job_postings_has_internal_inclusive');
            $table->dropColumn(['has_internal', 'has_internal_open', 'has_internal_inclusive', 'internal_inclusive_types']);
        });
    }
};
