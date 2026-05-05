<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Change applied_category from ENUM to JSON so it can store
     * multiple selected categories (e.g. ["open","inclusive"]).
     */
    public function up(): void
    {
        // Drop the index before altering the column
        Schema::table('application_form', function (Blueprint $table) {
            $table->dropIndex('idx_application_form_applied_category');
        });

        // Convert existing ENUM values to JSON arrays, then change column type
        DB::statement("ALTER TABLE application_form MODIFY applied_category JSON NULL");

        // Wrap any plain ENUM string values already in the DB into JSON arrays
        DB::statement("
            UPDATE application_form
            SET applied_category = JSON_ARRAY(applied_category)
            WHERE applied_category IS NOT NULL
              AND JSON_VALID(applied_category) = 0
        ");
    }

    public function down(): void
    {
        // Revert back to ENUM (loses multi-category data)
        DB::statement("ALTER TABLE application_form MODIFY applied_category ENUM('open','inclusive') NULL");

        Schema::table('application_form', function (Blueprint $table) {
            $table->index('applied_category', 'idx_application_form_applied_category');
        });
    }
};
