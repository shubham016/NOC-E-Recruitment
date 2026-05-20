<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\JobPosting;
use Carbon\Carbon;

class CloseExpiredVacancies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vacancies:close-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically close vacancies when deadline or double dastur date has passed';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Checking for expired vacancies...');

        $today = Carbon::today();

        // Close vacancies where double_dastur_date has passed (Open/Inclusive)
        // Close vacancies where deadline has passed and no double_dastur_date is set (Internal/Appraisal)
        $expiredVacancies = JobPosting::whereIn('status', ['active', 'draft'])
            ->where(function ($q) use ($today) {
                $q->where(function ($inner) use ($today) {
                    // Has double dastur date and it has passed
                    $inner->whereNotNull('double_dastur_date')
                          ->whereDate('double_dastur_date', '<', $today);
                })->orWhere(function ($inner) use ($today) {
                    // No double dastur date — close after regular deadline
                    $inner->whereNull('double_dastur_date')
                          ->whereDate('deadline', '<', $today);
                });
            })
            ->get();

        if ($expiredVacancies->isEmpty()) {
            $this->info('✅ No expired vacancies found.');
            return 0;
        }

        $this->info("📋 Found {$expiredVacancies->count()} expired vacancies.");

        $closedCount = 0;

        foreach ($expiredVacancies as $vacancy) {
            $vacancy->update(['status' => 'closed']);
            $closedCount++;

            $this->line("   ✓ Closed: {$vacancy->advertisement_no} - {$vacancy->position_level}");
        }

        $this->info("✅ Successfully closed {$closedCount} vacancies.");

        return 0;
    }
}
