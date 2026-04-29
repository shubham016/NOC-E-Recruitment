<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Strip the embedded level number from job posting titles.
     *
     * Titles were saved as "Position - Level" (e.g. "Assistant - 4").
     * The level is already stored in the separate `level` column, so the
     * title should contain only the position name (e.g. "Assistant").
     */
    public function up(): void
    {
        $jobs = DB::table('job_postings')
            ->whereNotNull('level')
            ->whereNotNull('title')
            ->get(['id', 'title', 'level']);

        foreach ($jobs as $job) {
            $suffix  = ' - ' . $job->level;
            $cleaned = $job->title;

            if (str_ends_with($cleaned, $suffix)) {
                $cleaned = trim(substr($cleaned, 0, strlen($cleaned) - strlen($suffix)));
                DB::table('job_postings')
                    ->where('id', $job->id)
                    ->update(['title' => $cleaned]);
            }
        }
    }

    public function down(): void
    {
        // Restore original format by re-appending level to title
        $jobs = DB::table('job_postings')
            ->whereNotNull('level')
            ->whereNotNull('title')
            ->get(['id', 'title', 'level']);

        foreach ($jobs as $job) {
            DB::table('job_postings')
                ->where('id', $job->id)
                ->update(['title' => $job->title . ' - ' . $job->level]);
        }
    }
};
