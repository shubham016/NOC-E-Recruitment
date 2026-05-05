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
        Schema::table('application_form', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        
        Schema::table('application_form', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected', 'shortlisted', 'selected'])
                ->default('pending')
                ->after('terms_agree');
        });

        Schema::table('application_form', function (Blueprint $table) {
            // Add exam-related columns
            $table->date('exam_date')->nullable()->after('status');
            $table->string('exam_time')->nullable()->after('exam_date');
            $table->text('exam_venue')->nullable()->after('exam_time');
            $table->string('reporting_time')->nullable()->after('exam_venue');
            $table->text('exam_instructions')->nullable()->after('reporting_time');
            $table->string('organization_name')->default('Online Recruitment Management System')->after('exam_instructions');
            $table->string('post_title')->nullable()->after('organization_name');
            $table->string('roll_number')->nullable()->unique()->after('post_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_form', function (Blueprint $table) {
            $table->dropColumn([
                'exam_date',
                'exam_time', 
                'exam_venue',
                'reporting_time',
                'exam_instructions',
                'organization_name',
                'post_title',
                'roll_number'
            ]);
            
            // Restore original status enum
            $table->dropColumn('status');
        });
        
        Schema::table('application_form', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])
                ->default('pending')
                ->after('terms_agree');
        });
    }
};