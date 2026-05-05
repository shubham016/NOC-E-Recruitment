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
        // First, copy service_group data to department column
        DB::statement('UPDATE job_postings SET department = service_group WHERE service_group IS NOT NULL AND service_group != ""');

        // Then drop the service_group column
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropColumn('service_group');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add service_group column
        Schema::table('job_postings', function (Blueprint $table) {
            $table->string('service_group')->after('department');
        });

        // Copy department data back to service_group
        DB::statement('UPDATE job_postings SET service_group = department');
    }
};
