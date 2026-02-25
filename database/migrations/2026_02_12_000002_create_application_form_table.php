<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Drop existing table if it exists
        Schema::dropIfExists('application_form');

        // Use raw SQL to create table with ROW_FORMAT=DYNAMIC from the start
        \DB::statement("
            CREATE TABLE `application_form` (
                `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `candidate_id` BIGINT UNSIGNED NOT NULL,
                `job_posting_id` BIGINT UNSIGNED NOT NULL,
                `reviewer_id` BIGINT UNSIGNED NULL,
                `status` ENUM('draft', 'pending', 'approved', 'rejected', 'shortlisted', 'selected') DEFAULT 'draft',

                -- Personal Info
                `name_english` VARCHAR(100) NULL,
                `name_nepali` VARCHAR(100) NULL,
                `email` VARCHAR(100) NULL,
                `advertisement_no` VARCHAR(50) NULL,
                `applying_position` VARCHAR(100) NULL,
                `department` VARCHAR(100) NULL,
                `alternate_phone_number` VARCHAR(20) NULL,

                -- General Info
                `religion` VARCHAR(50) NULL,
                `religion_other` VARCHAR(100) NULL,
                `community` VARCHAR(100) NULL,
                `community_other` VARCHAR(100) NULL,
                `ethnic_group` VARCHAR(50) NULL,
                `ethnic_group_other` VARCHAR(100) NULL,
                `ethnic_certificate` VARCHAR(255) NULL,
                `marital_status` VARCHAR(20) NULL,
                `employment_status` VARCHAR(50) NULL,
                `employment_other` VARCHAR(100) NULL,
                `physical_disability` VARCHAR(50) NULL,
                `disability_other` VARCHAR(100) NULL,
                `disability_certificate` VARCHAR(255) NULL,
                `mother_tongue` VARCHAR(50) NULL,
                `blood_group` VARCHAR(10) NULL,
                `noc_employee` VARCHAR(10) NULL,
                `noc_id_card` VARCHAR(255) NULL,

                -- Personal Details
                `birth_date_ad` DATE NULL,
                `birth_date_bs` VARCHAR(20) NULL,
                `age` INT NULL,
                `phone` VARCHAR(20) NULL,
                `gender` VARCHAR(10) NULL,

                -- Citizenship
                `citizenship_number` VARCHAR(50) NULL,
                `citizenship_issue_date_bs` VARCHAR(20) NULL,
                `citizenship_issue_date_ad` DATE NULL,
                `citizenship_issue_district` VARCHAR(100) NULL,
                `citizenship_certificate` VARCHAR(255) NULL,

                -- Family
                `father_name_english` VARCHAR(100) NULL,
                `father_name_nepali` VARCHAR(100) NULL,
                `father_qualification` VARCHAR(100) NULL,
                `mother_name_english` VARCHAR(100) NULL,
                `mother_name_nepali` VARCHAR(100) NULL,
                `mother_qualification` VARCHAR(100) NULL,
                `parent_occupation` VARCHAR(100) NULL,
                `parent_occupation_other` VARCHAR(100) NULL,
                `grandfather_name_english` VARCHAR(100) NULL,
                `grandfather_name_nepali` VARCHAR(100) NULL,
                `nationality` VARCHAR(50) NULL,
                `spouse_name_english` VARCHAR(100) NULL,
                `spouse_name_nepali` VARCHAR(100) NULL,
                `spouse_nationality` VARCHAR(50) NULL,

                -- Address
                `permanent_province` VARCHAR(50) NULL,
                `permanent_district` VARCHAR(50) NULL,
                `permanent_municipality` VARCHAR(100) NULL,
                `permanent_ward` VARCHAR(10) NULL,
                `permanent_tole` VARCHAR(100) NULL,
                `permanent_house_number` VARCHAR(20) NULL,
                `same_as_permanent` TINYINT(1) DEFAULT 0,
                `mailing_province` VARCHAR(50) NULL,
                `mailing_district` VARCHAR(50) NULL,
                `mailing_municipality` VARCHAR(100) NULL,
                `mailing_ward` VARCHAR(10) NULL,
                `mailing_tole` VARCHAR(100) NULL,
                `mailing_house_number` VARCHAR(20) NULL,

                -- Application
                `cover_letter` TEXT NULL,
                `years_of_experience` INT NULL,
                `relevant_experience` TEXT NULL,

                -- Education
                `education_level` VARCHAR(50) NULL,
                `field_of_study` VARCHAR(100) NULL,
                `institution_name` VARCHAR(200) NULL,
                `graduation_year` INT NULL,

                -- Work Experience
                `has_work_experience` VARCHAR(10) NULL,
                `previous_organization` VARCHAR(200) NULL,
                `previous_position` VARCHAR(100) NULL,

                -- Documents
                `passport_photo` VARCHAR(255) NULL,
                `resume` VARCHAR(255) NULL,
                `cover_letter_file` VARCHAR(255) NULL,
                `educational_certificates` VARCHAR(255) NULL,
                `character_certificate` VARCHAR(255) NULL,
                `equivalency_certificate` VARCHAR(255) NULL,
                `experience_certificates` VARCHAR(255) NULL,
                `signature` VARCHAR(255) NULL,
                `other_documents` VARCHAR(255) NULL,

                -- Terms
                `terms_agree` TINYINT(1) DEFAULT 0,

                -- Review
                `admin_notes` TEXT NULL,
                `reviewer_notes` TEXT NULL,
                `reviewed_at` TIMESTAMP NULL,
                `submitted_at` TIMESTAMP NULL,

                -- Admit Card
                `exam_date` VARCHAR(50) NULL,
                `exam_time` VARCHAR(20) NULL,
                `exam_venue` VARCHAR(200) NULL,
                `roll_number` VARCHAR(50) NULL,
                `admit_card_generated` TINYINT(1) DEFAULT 0,

                `created_at` TIMESTAMP NULL,
                `updated_at` TIMESTAMP NULL,

                UNIQUE KEY `unique_candidate_job_application` (`candidate_id`, `job_posting_id`),
                FOREIGN KEY (`candidate_id`) REFERENCES `candidates`(`id`) ON DELETE CASCADE,
                FOREIGN KEY (`job_posting_id`) REFERENCES `job_postings`(`id`) ON DELETE CASCADE,
                FOREIGN KEY (`reviewer_id`) REFERENCES `reviewers`(`id`) ON DELETE SET NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('application_form');
    }
};
