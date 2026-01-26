<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('application_form');
        
        Schema::create('application_form', function (Blueprint $table) {
            $table->id();
            
            // General Information
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
            
            // Personal Information - Basic
            $table->date('birth_date_ad')->nullable(); // जन्म मिति (A.D)
            $table->string('birth_date_bs')->nullable(); // जन्म मिति (B.S)
            $table->integer('age')->nullable(); // उमेर
            $table->string('phone')->nullable(); // फोन नम्बर
            $table->string('gender')->nullable(); // लिङ्ग
            
            // Citizenship
            $table->string('citizenship_number')->nullable(); // नागरिकता नम्बर
            $table->string('citizenship_issue_date_bs')->nullable(); // जारी मिति(B.S)
            $table->date('citizenship_issue_date_ad')->nullable(); // जारी मिति(A.D)
            $table->string('citizenship_issue_district')->nullable(); // जारी जिल्ला
            
            // Family Information
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
            
            // Permanent Address
            $table->string('permanent_province')->nullable(); // प्रान्त
            $table->string('permanent_district')->nullable(); // जिल्ला
            $table->string('permanent_municipality')->nullable(); // नगरपालिका / गा.वि.स
            $table->string('permanent_ward')->nullable(); // वडा नम्बर
            $table->string('permanent_tole')->nullable(); // टोलको नाम
            $table->string('permanent_house_number')->nullable(); // घर नम्बर
            
            // Mailing Address
            $table->boolean('same_as_permanent')->default(false); // स्थायी ठेगाना जस्तै
            $table->string('mailing_province')->nullable(); // प्रान्त
            $table->string('mailing_district')->nullable(); // जिल्ला
            $table->string('mailing_municipality')->nullable(); // नगरपालिका / गा.वि.स
            $table->string('mailing_ward')->nullable(); // वडा नम्बर
            $table->string('mailing_tole')->nullable(); // टोलको नाम
            $table->string('mailing_house_number')->nullable(); // घर नम्बर
            
            // Missing
            $table->string('name_english')->nullable(); 
            $table->string('name_nepali')->nullable(); 
            $table->string('education_level')->nullable(); 
            $table->string('field_of_study')->nullable(); 
            $table->integer('graduation_year')->nullable();
            $table->string('has_work_experience')->nullable();
            $table->integer('years_of_experience')->nullable();
            $table->string('previous_organization')->nullable(); 
            $table->string('previous_position')->nullable();   
            $table->string('citizenship_id_document')->nullable(); 
            $table->string('resume_cv')->nullable(); 
            $table->string('educational_certificates')->nullable();  
            $table->string('passport_size_photo')->nullable(); 
            $table->string('institution_name')->nullable(); 
            $table->string('terms_agree')->nullable();
            $table->enum('status', ['draft','pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('application_form');
    }
};