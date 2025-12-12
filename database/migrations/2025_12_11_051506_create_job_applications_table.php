<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained('candidates')->onDelete('cascade');
            $table->foreignId('job_posting_id')->constrained('job_postings')->onDelete('cascade');
            $table->foreignId('reviewer_id')->nullable()->constrained('reviewers')->onDelete('set null');

            // ======================
            // GENERAL INFORMATION
            // ======================
            $table->string('religion')->nullable(); // धर्म
            $table->string('religion_other')->nullable();
            $table->string('community')->nullable(); // तपाई आफैलाई के बोलाउन रुचाउनुहुन्छ
            $table->string('community_other')->nullable();
            $table->string('ethnic_group')->nullable(); // जातीय समूह
            $table->string('ethnic_group_other')->nullable();
            $table->string('ethnic_certificate')->nullable(); // Certificate upload
            $table->string('marital_status')->nullable(); // वैवाहिक स्थिति
            $table->string('employment_status')->nullable(); // रोजगारी अवस्था
            $table->string('employment_other')->nullable();
            $table->string('physical_disability')->nullable(); // शारीरिक अशक्त
            $table->string('disability_other')->nullable();
            $table->string('disability_certificate')->nullable(); // Disability certificate
            $table->string('mother_tongue')->nullable(); // मातृभाषा
            $table->string('blood_group')->nullable(); // रक्त समूह
            $table->string('noc_employee')->nullable(); // NOC कर्मचारी
            $table->string('noc_id_card')->nullable(); // NOC ID Card upload

            // ======================
            // PERSONAL INFORMATION
            // ======================
            $table->date('birth_date_ad')->nullable(); // जन्म मिति (A.D)
            $table->string('birth_date_bs')->nullable(); // जन्म मिति (B.S)
            $table->integer('age')->nullable(); // उमेर
            $table->string('phone')->nullable(); // फोन नम्बर
            $table->string('gender')->nullable(); // लिङ्ग

            // ======================
            // CITIZENSHIP INFORMATION
            // ======================
            $table->string('citizenship_number')->nullable(); // नागरिकता नम्बर
            $table->string('citizenship_issue_date_bs')->nullable(); // जारी मिति(B.S)
            $table->date('citizenship_issue_date_ad')->nullable(); // जारी मिति(A.D)
            $table->string('citizenship_issue_district')->nullable(); // जारी जिल्ला
            $table->string('citizenship_certificate')->nullable(); // Citizenship document

            // ======================
            // FAMILY INFORMATION
            // ======================
            $table->string('father_name_english')->nullable(); // बुबाको नाम (English)
            $table->string('father_name_nepali')->nullable(); // बुबाको नाम (नेपालीमा)
            $table->string('father_qualification')->nullable(); // बुबाको योग्यता
            $table->string('mother_name_english')->nullable(); // आमाको नाम (English)
            $table->string('mother_name_nepali')->nullable(); // आमाको नाम (नेपालीमा)
            $table->string('mother_qualification')->nullable(); // आमाको योग्यता
            $table->string('parent_occupation')->nullable(); // बुबा/आमाको मुख्य पेशा
            $table->string('parent_occupation_other')->nullable();
            $table->string('grandfather_name_english')->nullable(); // हजुरबुबाको नाम (English)
            $table->string('grandfather_name_nepali')->nullable(); // हजुरबुबाको नाम (नेपालीमा)
            $table->string('nationality')->nullable(); // राष्ट्रियता
            $table->string('spouse_name_english')->nullable(); // पति/पत्नीको नाम (English)
            $table->string('spouse_name_nepali')->nullable(); // पति/पत्नीको नाम (नेपालीमा)
            $table->string('spouse_nationality')->nullable(); // पति/पत्नी राष्ट्रियता

            // ======================
            // PERMANENT ADDRESS
            // ======================
            $table->string('permanent_province')->nullable(); // प्रान्त
            $table->string('permanent_district')->nullable(); // जिल्ला
            $table->string('permanent_municipality')->nullable(); // नगरपालिका / गा.वि.स
            $table->string('permanent_ward')->nullable(); // वडा नम्बर
            $table->string('permanent_tole')->nullable(); // टोलको नाम
            $table->string('permanent_house_number')->nullable(); // घर नम्बर

            // ======================
            // MAILING ADDRESS
            // ======================
            $table->boolean('same_as_permanent')->default(false); // स्थायी ठेगाना जस्तै
            $table->string('mailing_province')->nullable(); // प्रान्त
            $table->string('mailing_district')->nullable(); // जिल्ला
            $table->string('mailing_municipality')->nullable(); // नगरपालिका / गा.वि.स
            $table->string('mailing_ward')->nullable(); // वडा नम्बर
            $table->string('mailing_tole')->nullable(); // टोलको नाम
            $table->string('mailing_house_number')->nullable(); // घर नम्बर

            // ======================
            // JOB APPLICATION SPECIFIC
            // ======================
            $table->text('cover_letter')->nullable();
            $table->integer('years_of_experience')->nullable();
            $table->text('relevant_experience')->nullable();
            $table->string('current_salary')->nullable();
            $table->string('expected_salary')->nullable();
            $table->date('available_from')->nullable();

            // ======================
            // DOCUMENTS (File Paths)
            // ======================
            $table->string('passport_photo')->nullable(); // Passport size photo
            $table->string('resume')->nullable(); // Resume/CV
            $table->string('cover_letter_file')->nullable(); // Cover letter document
            $table->string('educational_certificates')->nullable(); // Educational documents
            $table->string('experience_certificates')->nullable(); // Experience certificates
            $table->string('other_documents')->nullable(); // Other supporting documents

            // ======================
            // APPLICATION STATUS
            // ======================
            $table->enum('status', ['pending', 'under_review', 'shortlisted', 'rejected', 'withdrawn'])->default('pending');
            $table->text('admin_notes')->nullable();
            $table->text('reviewer_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();

            // Prevent duplicate applications
            $table->unique(['candidate_id', 'job_posting_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};