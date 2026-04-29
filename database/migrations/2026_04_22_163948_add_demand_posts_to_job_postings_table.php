<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * PURPOSE: Store per-type demand post breakdown for multi-category vacancies.
     * e.g. {"has_open":5,"incl_women":2,"incl_dalit":1}
     * number_of_posts remains the computed total.
     */
    public function up(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->json('demand_posts')->nullable()
                ->after('number_of_posts')
                ->comment('Per-type demand breakdown as JSON, e.g. {"has_open":5,"incl_women":2}');
        });
    }

    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropColumn('demand_posts');
        });
    }
};
