<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmployeeIdToCandidateRegistrationTable extends Migration
{
    public function up(): void
    {
        Schema::table('candidate_registration', function (Blueprint $table) {
            $table->string('employee_id')->nullable()->after('noc_employee');
        });
    }

    public function down(): void
    {
        Schema::table('candidate_registration', function (Blueprint $table) {
            $table->dropColumn('employee_id');
        });
    }
}