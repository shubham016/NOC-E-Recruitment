<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidate_registration', function (Blueprint $table) {
            if (!Schema::hasColumn('candidate_registration', 'noc_employee')) {
                $table->string('noc_employee', 3)->nullable()->after('gender'); // 'yes' or 'no'
            }
            if (!Schema::hasColumn('candidate_registration', 'nid')) {
                $table->string('nid')->nullable()->after('citizenship_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('candidate_registration', function (Blueprint $table) {
            if (Schema::hasColumn('candidate_registration', 'noc_employee')) {
                $table->dropColumn('noc_employee');
            }
            if (Schema::hasColumn('candidate_registration', 'nid')) {
                $table->dropColumn('nid');
            }
        });
    }
};
