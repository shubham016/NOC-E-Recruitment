<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            if (!Schema::hasColumn('job_postings', 'service_group')) {
                $table->string('service_group')->nullable()->after('department');
            }
        });

        // Sync existing department data into service_group
        DB::table('job_postings')->update(['service_group' => DB::raw('department')]);
    }

    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            if (Schema::hasColumn('job_postings', 'service_group')) {
                $table->dropColumn('service_group');
            }
        });
    }
};
