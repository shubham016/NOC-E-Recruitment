<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * PURPOSE: Track which category (open/inclusive) a candidate applied under
     * when the vacancy supports multiple categories.
     */
    public function up(): void
    {
        Schema::table('application_form', function (Blueprint $table) {
            // Track which category the candidate applied under
            $table->enum('applied_category', ['open', 'inclusive'])
                ->nullable()
                ->after('job_posting_id')
                ->comment('Category candidate applied under (open/inclusive)');

            // Add index for filtering by category
            $table->index('applied_category', 'idx_application_form_applied_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_form', function (Blueprint $table) {
            $table->dropIndex('idx_application_form_applied_category');
            $table->dropColumn('applied_category');
        });
    }
};
