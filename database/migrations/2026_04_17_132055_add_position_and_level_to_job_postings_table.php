<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->string('position')->nullable()->after('title');
            $table->unsignedSmallInteger('level')->nullable()->after('position');
        });

        // Migrate existing data: pull position text from old position_level string
        // level stays null — old dropdown values were not clean integers
        DB::table('job_postings')->get()->each(function ($job) {
            $positionLevel = $job->position_level ?? '';
            $dashPos = strpos($positionLevel, ' - ');
            $position = $dashPos !== false
                ? trim(substr($positionLevel, 0, $dashPos))
                : trim($positionLevel);

            DB::table('job_postings')->where('id', $job->id)->update([
                'position' => $position ?: null,
            ]);
        });

        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropColumn('position_level');
        });
    }

    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->string('position_level')->nullable()->after('title');
        });

        DB::table('job_postings')->get()->each(function ($job) {
            $combined = $job->position . ($job->level ? ' - ' . $job->level : '');
            DB::table('job_postings')->where('id', $job->id)->update([
                'position_level' => $combined,
            ]);
        });

        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropColumn(['position', 'level']);
        });
    }
};
