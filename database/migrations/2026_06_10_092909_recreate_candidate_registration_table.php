<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('candidate_registration');

        // Force DYNAMIC row format to support large rows in InnoDB
        DB::statement('SET SESSION innodb_strict_mode=OFF');

        Schema::create('candidate_registration', function (Blueprint $table) {
            $table->id();

            // ── Account ───────────────────────────────────────────────────
            $table->string('email', 150)->nullable()->unique();
            $table->string('password', 255)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('alternate_phone_number', 20)->nullable();

            // ── Personal ──────────────────────────────────────────────────
            $table->string('name_english', 150)->nullable();
            $table->string('name_nepali', 150)->nullable();
            $table->string('gender', 10)->nullable();
            $table->date('birth_date_ad')->nullable();
            $table->string('birth_date_bs', 20)->nullable();
            $table->string('date_of_birth_bs', 20)->nullable();
            $table->string('age', 50)->nullable();
            $table->string('marital_status', 20)->nullable();
            $table->string('blood_group', 5)->nullable();
            $table->string('nationality', 50)->nullable();
            $table->string('mother_tongue', 50)->nullable();

            // ── Citizenship ───────────────────────────────────────────────
            $table->string('citizenship_number', 50)->nullable()->unique();
            $table->string('citizenship_issue_date_bs', 20)->nullable();
            $table->date('citizenship_issue_date_ad')->nullable();
            $table->string('citizenship_issue_district', 100)->nullable();

            // ── Identity / Employment ─────────────────────────────────────
            $table->string('nid', 50)->nullable()->unique();
            $table->string('noc_employee', 5)->nullable();
            $table->string('employee_id', 50)->nullable();
            $table->string('employment_status', 30)->nullable();
            $table->string('employment_other', 100)->nullable();

            // ── Family ────────────────────────────────────────────────────
            $table->string('father_name_english', 100)->nullable();
            $table->string('father_name_nepali', 100)->nullable();
            $table->string('mother_name_english', 100)->nullable();
            $table->string('mother_name_nepali', 100)->nullable();
            $table->string('grandfather_name_english', 100)->nullable();
            $table->string('grandfather_name_nepali', 100)->nullable();
            $table->string('spouse_name_english', 100)->nullable();
            $table->string('spouse_nationality', 50)->nullable();

            // ── General / Demographic ─────────────────────────────────────
            $table->string('religion', 30)->nullable();
            $table->string('religion_other', 100)->nullable();
            $table->string('community', 30)->nullable();
            $table->string('community_other', 100)->nullable();
            $table->string('ethnic_group', 30)->nullable();
            $table->string('ethnic_group_other', 100)->nullable();
            $table->string('physical_disability', 5)->nullable();
            $table->string('disability_other', 100)->nullable();

            // ── Permanent Address ─────────────────────────────────────────
            $table->string('permanent_province', 50)->nullable();
            $table->string('permanent_district', 100)->nullable();
            $table->string('permanent_municipality', 100)->nullable();
            $table->string('permanent_ward', 10)->nullable();
            $table->string('permanent_tole', 100)->nullable();
            $table->string('permanent_house_number', 20)->nullable();

            // ── Mailing Address ───────────────────────────────────────────
            $table->boolean('same_as_permanent')->default(false);
            $table->string('mailing_province', 50)->nullable();
            $table->string('mailing_district', 100)->nullable();
            $table->string('mailing_municipality', 100)->nullable();
            $table->string('mailing_ward', 10)->nullable();
            $table->string('mailing_tole', 100)->nullable();
            $table->string('mailing_house_number', 20)->nullable();

            // ── Education ─────────────────────────────────────────────────
            $table->string('education_level', 50)->nullable();
            $table->string('field_of_study', 100)->nullable();
            $table->string('institution_name', 150)->nullable();
            $table->string('university', 150)->nullable();
            $table->string('graduation_year', 4)->nullable();
            $table->string('graduation_year_english', 4)->nullable();

            // ── Work Experience ───────────────────────────────────────────
            $table->string('has_work_experience', 5)->nullable();

            foreach (range(1, 10) as $i) {
                $table->string("exp{$i}_organization", 150)->nullable();
                $table->string("exp{$i}_position", 100)->nullable();
                $table->string("exp{$i}_start_date_bs", 20)->nullable();
                $table->date("exp{$i}_start_date")->nullable();
                $table->string("exp{$i}_end_date_bs", 20)->nullable();
                $table->date("exp{$i}_end_date")->nullable();
                $table->decimal("exp{$i}_years", 4, 1)->nullable();
                $table->string("exp{$i}_document", 255)->nullable();
            }

            // ── Uploaded Documents ────────────────────────────────────────
            $table->string('passport_size_photo', 255)->nullable();
            $table->string('signature', 255)->nullable();
            $table->string('citizenship_id_document', 255)->nullable();
            $table->string('noc_id_card', 255)->nullable();
            $table->string('disability_certificate', 255)->nullable();
            $table->string('ethnic_certificate', 255)->nullable();
            $table->string('transcript', 255)->nullable();
            $table->string('character_certificate', 255)->nullable();
            $table->string('equivalency_certificate', 255)->nullable();

            $table->rememberToken();
            $table->timestamps();
        });

        // Apply DYNAMIC row format explicitly
        DB::statement('ALTER TABLE candidate_registration ROW_FORMAT=DYNAMIC');
    }

    public function down(): void
    {
        Schema::dropIfExists('candidate_registration');
    }
};