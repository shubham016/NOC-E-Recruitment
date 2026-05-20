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
        Schema::table('job_postings', function (Blueprint $table) {
            $table->unsignedTinyInteger('min_age_male')->nullable()->after('max_age');
            $table->unsignedTinyInteger('max_age_male')->nullable()->after('min_age_male');
            $table->unsignedTinyInteger('min_age_female')->nullable()->after('max_age_male');
            $table->unsignedTinyInteger('max_age_female')->nullable()->after('min_age_female');
            $table->unsignedTinyInteger('min_age_disabled')->nullable()->after('max_age_female');
            $table->unsignedTinyInteger('max_age_disabled')->nullable()->after('min_age_disabled');
        });
    }

    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropColumn([
                'min_age_male', 'max_age_male',
                'min_age_female', 'max_age_female',
                'min_age_disabled', 'max_age_disabled',
            ]);
        });
    }
};
