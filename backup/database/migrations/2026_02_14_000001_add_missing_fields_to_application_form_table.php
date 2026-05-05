<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Build a single ALTER TABLE statement to change ROW_FORMAT and add all missing columns
        // This avoids repeated row-size checks between individual column additions
        $columns = [];

        // Personal Information Fields
        if (!\Schema::hasColumn('application_form', 'name_english')) {
            $columns[] = 'ADD COLUMN `name_english` VARCHAR(255) NULL';
        }
        if (!\Schema::hasColumn('application_form', 'name_nepali')) {
            $columns[] = 'ADD COLUMN `name_nepali` VARCHAR(255) NULL';
        }
        if (!\Schema::hasColumn('application_form', 'email')) {
            $columns[] = 'ADD COLUMN `email` VARCHAR(255) NULL';
        }
        if (!\Schema::hasColumn('application_form', 'advertisement_no')) {
            $columns[] = 'ADD COLUMN `advertisement_no` VARCHAR(255) NULL';
        }
        if (!\Schema::hasColumn('application_form', 'applying_position')) {
            $columns[] = 'ADD COLUMN `applying_position` VARCHAR(255) NULL';
        }
        if (!\Schema::hasColumn('application_form', 'department')) {
            $columns[] = 'ADD COLUMN `department` VARCHAR(255) NULL';
        }
        if (!\Schema::hasColumn('application_form', 'alternate_phone_number')) {
            $columns[] = 'ADD COLUMN `alternate_phone_number` VARCHAR(20) NULL';
        }

        // Educational Background Fields
        if (!\Schema::hasColumn('application_form', 'education_level')) {
            $columns[] = 'ADD COLUMN `education_level` VARCHAR(255) NULL';
        }
        if (!\Schema::hasColumn('application_form', 'field_of_study')) {
            $columns[] = 'ADD COLUMN `field_of_study` VARCHAR(255) NULL';
        }
        if (!\Schema::hasColumn('application_form', 'institution_name')) {
            $columns[] = 'ADD COLUMN `institution_name` VARCHAR(255) NULL';
        }
        if (!\Schema::hasColumn('application_form', 'graduation_year')) {
            $columns[] = 'ADD COLUMN `graduation_year` INT NULL';
        }

        // Work Experience Fields
        if (!\Schema::hasColumn('application_form', 'has_work_experience')) {
            $columns[] = 'ADD COLUMN `has_work_experience` VARCHAR(10) NULL';
        }
        if (!\Schema::hasColumn('application_form', 'previous_organization')) {
            $columns[] = 'ADD COLUMN `previous_organization` VARCHAR(255) NULL';
        }
        if (!\Schema::hasColumn('application_form', 'previous_position')) {
            $columns[] = 'ADD COLUMN `previous_position` VARCHAR(255) NULL';
        }

        // Document Fields
        if (!\Schema::hasColumn('application_form', 'character_certificate')) {
            $columns[] = 'ADD COLUMN `character_certificate` VARCHAR(255) NULL';
        }
        if (!\Schema::hasColumn('application_form', 'equivalency_certificate')) {
            $columns[] = 'ADD COLUMN `equivalency_certificate` VARCHAR(255) NULL';
        }
        if (!\Schema::hasColumn('application_form', 'signature')) {
            $columns[] = 'ADD COLUMN `signature` VARCHAR(255) NULL';
        }

        // Terms
        if (!\Schema::hasColumn('application_form', 'terms_agree')) {
            $columns[] = 'ADD COLUMN `terms_agree` TINYINT(1) NULL DEFAULT 0';
        }

        // Always include ROW_FORMAT=DYNAMIC to resolve future row size issues
        $columnsSql = implode(', ', $columns);
        if ($columnsSql) {
            \DB::statement("ALTER TABLE `application_form` ROW_FORMAT=DYNAMIC, {$columnsSql}");
        } else {
            \DB::statement('ALTER TABLE `application_form` ROW_FORMAT=DYNAMIC');
        }
    }

    public function down(): void
    {
        $toDrop = [
            'name_english', 'name_nepali', 'email', 'advertisement_no',
            'applying_position', 'department', 'alternate_phone_number',
            'education_level', 'field_of_study', 'institution_name', 'graduation_year',
            'has_work_experience', 'previous_organization', 'previous_position',
            'character_certificate', 'equivalency_certificate', 'signature', 'terms_agree',
        ];

        $existing = array_filter($toDrop, fn($col) => \Schema::hasColumn('application_form', $col));

        if (!empty($existing)) {
            Schema::table('application_form', function (Blueprint $table) use ($existing) {
                $table->dropColumn(array_values($existing));
            });
        }
    }
};
