<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('application_form', 'vacancy_id') && !Schema::hasColumn('application_form', 'job_posting_id')) {
            Schema::table('application_form', function (Blueprint $table) {
                $table->renameColumn('vacancy_id', 'job_posting_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('application_form', 'job_posting_id') && !Schema::hasColumn('application_form', 'vacancy_id')) {
            Schema::table('application_form', function (Blueprint $table) {
                $table->renameColumn('job_posting_id', 'vacancy_id');
            });
        }
    }
};
