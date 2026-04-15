<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * PURPOSE: Enable multi-category vacancy system where a single vacancy can accept
     * applications from both "Open" and "Inclusive" categories simultaneously.
     *
     * BEFORE: Admin could only select ONE category (open OR inclusive)
     * AFTER: Admin can select BOTH categories (open AND inclusive)
     */
    public function up(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            // Multi-category support flags
            $table->boolean('has_open')->default(false)
                ->after('category')
                ->comment('Whether this vacancy accepts Open category applications');

            $table->boolean('has_inclusive')->default(false)
                ->after('has_open')
                ->comment('Whether this vacancy accepts Inclusive category applications');

            // Number of posts per category (optional split)
            $table->unsignedInteger('open_posts')->nullable()
                ->after('has_inclusive')
                ->comment('Number of posts allocated for Open category (null = not specified)');

            $table->unsignedInteger('inclusive_posts')->nullable()
                ->after('open_posts')
                ->comment('Number of posts allocated for Inclusive category (null = not specified)');

            // Add indexes for filtering performance
            $table->index('has_open', 'idx_job_postings_has_open');
            $table->index('has_inclusive', 'idx_job_postings_has_inclusive');
            $table->index(['has_open', 'has_inclusive'], 'idx_job_postings_categories');
        });

        // Migrate existing data: Set flags based on current category value
        DB::table('job_postings')->where('category', 'open')->update(['has_open' => true]);
        DB::table('job_postings')->where('category', 'inclusive')->update(['has_inclusive' => true]);

        // For internal categories, check internal_type
        DB::table('job_postings')
            ->where('category', 'internal')
            ->where('internal_type', 'open')
            ->update(['has_open' => true]);

        DB::table('job_postings')
            ->where('category', 'internal')
            ->where('internal_type', 'inclusive')
            ->update(['has_inclusive' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            // Remove indexes
            $table->dropIndex('idx_job_postings_has_open');
            $table->dropIndex('idx_job_postings_has_inclusive');
            $table->dropIndex('idx_job_postings_categories');

            // Remove columns
            $table->dropColumn([
                'has_open',
                'has_inclusive',
                'open_posts',
                'inclusive_posts'
            ]);
        });
    }
};
