<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use App\Models\Candidate;
use App\Models\Reviewer;

class VerifyNotifications extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'notifications:verify {--user-id= : Specific user ID to check} {--user-type= : User type (candidate/reviewer)}';

    /**
     * The console command description.
     */
    protected $description = 'Verify notification isolation and display notification details';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== Notification Verification Tool ===');
        $this->newLine();

        if ($this->option('user-id') && $this->option('user-type')) {
            // Check specific user
            $this->checkUserNotifications(
                $this->option('user-id'),
                $this->option('user-type')
            );
        } else {
            // Show overview
            $this->showOverview();
        }
    }

    private function showOverview()
    {
        $this->info('Recent Notifications (Last 20):');
        $this->newLine();

        $notifications = Notification::latest()
            ->take(20)
            ->get();

        if ($notifications->isEmpty()) {
            $this->warn('No notifications found in database.');
            return;
        }

        $tableData = [];
        foreach ($notifications as $notification) {
            $tableData[] = [
                'ID' => $notification->id,
                'User ID' => $notification->user_id,
                'Type' => $notification->user_type,
                'Title' => substr($notification->title, 0, 30),
                'Is Read' => $notification->is_read ? 'Yes' : 'No',
                'Created' => $notification->created_at->diffForHumans(),
            ];
        }

        $this->table(
            ['ID', 'User ID', 'Type', 'Title', 'Is Read', 'Created'],
            $tableData
        );

        $this->newLine();
        $this->info('Statistics:');
        $this->line('Total Notifications: ' . Notification::count());
        $this->line('Candidate Notifications: ' . Notification::where('user_type', 'candidate')->count());
        $this->line('Reviewer Notifications: ' . Notification::where('user_type', 'reviewer')->count());
        $this->line('Admin Notifications: ' . Notification::where('user_type', 'admin')->count());
        $this->line('Unread Notifications: ' . Notification::where('is_read', false)->count());
    }

    private function checkUserNotifications($userId, $userType)
    {
        $this->info("Checking notifications for User ID: {$userId} (Type: {$userType})");
        $this->newLine();

        // Verify user exists
        if ($userType === 'candidate') {
            $user = Candidate::find($userId);
            if (!$user) {
                $this->error("Candidate with ID {$userId} not found!");
                return;
            }
            $this->info("User: {$user->first_name} {$user->last_name} ({$user->email})");
        } elseif ($userType === 'reviewer') {
            $user = Reviewer::find($userId);
            if (!$user) {
                $this->error("Reviewer with ID {$userId} not found!");
                return;
            }
            $this->info("User: {$user->name} ({$user->email})");
        }

        $this->newLine();

        // Get notifications
        $notifications = Notification::forUser($userId, $userType)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($notifications->isEmpty()) {
            $this->warn("No notifications found for this user.");
            return;
        }

        $this->info("Found {$notifications->count()} notification(s):");
        $this->newLine();

        foreach ($notifications as $notification) {
            $this->line("─────────────────────────────────────");
            $this->line("ID: {$notification->id}");
            $this->line("Title: {$notification->title}");
            $this->line("Type: {$notification->type}");
            $this->line("Message: {$notification->message}");
            $this->line("Is Read: " . ($notification->is_read ? 'Yes' : 'No'));
            $this->line("Created: {$notification->created_at}");
            if ($notification->read_at) {
                $this->line("Read At: {$notification->read_at}");
            }
            $this->newLine();
        }

        // Check for potential issues
        $this->checkForIssues($userId, $userType);
    }

    private function checkForIssues($userId, $userType)
    {
        $this->info('Running Integrity Checks:');
        $this->newLine();

        // Check 1: Notifications with wrong user_type
        $wrongType = Notification::where('user_id', $userId)
            ->where('user_type', '!=', $userType)
            ->count();

        if ($wrongType > 0) {
            $this->error("⚠ Found {$wrongType} notification(s) with user_id={$userId} but different user_type!");
        } else {
            $this->info('✓ No cross-contamination detected');
        }

        // Check 2: Duplicate notifications
        $duplicates = Notification::where('user_id', $userId)
            ->where('user_type', $userType)
            ->select('type', 'related_id', 'related_type')
            ->groupBy('type', 'related_id', 'related_type')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        if ($duplicates->isNotEmpty()) {
            $this->warn("⚠ Found potential duplicate notifications");
        } else {
            $this->info('✓ No duplicate notifications');
        }

        // Check 3: Orphaned notifications (related records deleted)
        $orphaned = Notification::where('user_id', $userId)
            ->where('user_type', $userType)
            ->whereNotNull('related_id')
            ->get()
            ->filter(function ($notification) {
                return $notification->getRelatedModel() === null;
            });

        if ($orphaned->isNotEmpty()) {
            $this->warn("⚠ Found {$orphaned->count()} orphaned notification(s) (related record deleted)");
        } else {
            $this->info('✓ No orphaned notifications');
        }
    }
}
