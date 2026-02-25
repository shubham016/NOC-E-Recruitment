<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            if (!Schema::hasColumn('candidates', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('mobile_number');
            }
            if (!Schema::hasColumn('candidates', 'date_of_birth_bs')) {
                $table->string('date_of_birth_bs', 20)->nullable()->after('gender');
            }
            if (!Schema::hasColumn('candidates', 'citizenship_number')) {
                $table->string('citizenship_number')->nullable()->unique()->after('date_of_birth_bs');
            }
            if (!Schema::hasColumn('candidates', 'citizenship_issue_district')) {
                $table->string('citizenship_issue_district')->nullable()->after('citizenship_number');
            }
            if (!Schema::hasColumn('candidates', 'citizenship_issue_date_bs')) {
                $table->string('citizenship_issue_date_bs', 20)->nullable()->after('citizenship_issue_district');
            }
        });
    }

    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $table->dropColumn([
                'gender',
                'date_of_birth_bs',
                'citizenship_number',
                'citizenship_issue_district',
                'citizenship_issue_date_bs',
            ]);
        });
    }
};
