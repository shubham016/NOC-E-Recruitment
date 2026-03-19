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
        // Step 1: Drop foreign key constraints from dependent tables
        // Get all foreign keys for job_posting_id
        $foreignKeys = DB::select("
            SELECT TABLE_NAME, CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND COLUMN_NAME = 'job_posting_id'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        // Drop each foreign key constraint
        foreach ($foreignKeys as $fk) {
            DB::statement("ALTER TABLE {$fk->TABLE_NAME} DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
        }

        // Step 2: Rename the main table
        Schema::rename('job_postings', 'vacancies');

        // Step 3: Rename foreign key columns in dependent tables
        // Get all tables with job_posting_id column
        $tablesWithJobPostingId = DB::select("
            SELECT DISTINCT TABLE_NAME
            FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
            AND COLUMN_NAME = 'job_posting_id'
        ");

        foreach ($tablesWithJobPostingId as $tableInfo) {
            $tableName = $tableInfo->TABLE_NAME;

            // Rename the column
            Schema::table($tableName, function (Blueprint $table) {
                $table->renameColumn('job_posting_id', 'vacancy_id');
            });
        }

        // Step 4: Re-establish foreign key constraints with new references
        // For approvers table (uses Laravel naming)
        Schema::table('approvers', function (Blueprint $table) {
            $table->foreign('vacancy_id')
                  ->references('id')
                  ->on('vacancies')
                  ->onDelete('set null');
        });

        // For application_form table (using raw SQL)
        DB::statement("
            ALTER TABLE application_form
            ADD CONSTRAINT application_form_vacancy_id_foreign
            FOREIGN KEY (vacancy_id) REFERENCES vacancies(id) ON DELETE CASCADE
        ");

        // Note: applications table doesn't have a foreign key constraint, so we don't add one

        // Step 5: Update polymorphic relationships in notifications table
        DB::table('notifications')
            ->where('related_type', 'App\\Models\\JobPosting')
            ->update(['related_type' => 'App\\Models\\Vacancy']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse Step 5: Restore polymorphic relationships
        DB::table('notifications')
            ->where('related_type', 'App\\Models\\Vacancy')
            ->update(['related_type' => 'App\\Models\\JobPosting']);

        // Reverse Step 4: Drop new foreign key constraints
        $foreignKeys = DB::select("
            SELECT TABLE_NAME, CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND COLUMN_NAME = 'vacancy_id'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        foreach ($foreignKeys as $fk) {
            DB::statement("ALTER TABLE {$fk->TABLE_NAME} DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
        }

        // Reverse Step 3: Rename columns back
        $tablesWithVacancyId = DB::select("
            SELECT DISTINCT TABLE_NAME
            FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
            AND COLUMN_NAME = 'vacancy_id'
        ");

        foreach ($tablesWithVacancyId as $tableInfo) {
            $tableName = $tableInfo->TABLE_NAME;

            // Rename the column back
            Schema::table($tableName, function (Blueprint $table) {
                $table->renameColumn('vacancy_id', 'job_posting_id');
            });
        }

        // Reverse Step 2: Rename table back
        Schema::rename('vacancies', 'job_postings');

        // Reverse Step 1: Re-establish original foreign keys
        Schema::table('approvers', function (Blueprint $table) {
            $table->foreign('job_posting_id')
                  ->references('id')
                  ->on('job_postings')
                  ->onDelete('set null');
        });

        DB::statement("
            ALTER TABLE application_form
            ADD CONSTRAINT application_form_job_posting_id_foreign
            FOREIGN KEY (job_posting_id) REFERENCES job_postings(id) ON DELETE CASCADE
        ");
    }
};
