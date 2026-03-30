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

        // Find all active or draft vacancies where deadline has passed
        $expiredByDeadline = JobPosting::whereIn('status', ['active', 'draft'])
            ->whereDate('deadline', '<', $today)
            ->get();

        // Find all active or draft vacancies where double dastur date has passed
        $expiredByDoubleDastur = JobPosting::whereIn('status', ['active', 'draft'])
            ->whereNotNull('double_dastur_date')
            ->whereDate('double_dastur_date', '<', $today)
            ->get();

        // Combine both collections and get unique vacancies
        $expiredVacancies = $expiredByDeadline->merge($expiredByDoubleDastur)->unique('id');

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
