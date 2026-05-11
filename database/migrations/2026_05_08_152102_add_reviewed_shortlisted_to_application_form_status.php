<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE application_form MODIFY COLUMN status ENUM(
            'draft','submitted','approved','rejected','edit','edited',
            'pending','assigned','under_review','reviewed','shortlisted','selected'
        ) NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE application_form MODIFY COLUMN status ENUM(
            'draft','submitted','approved','rejected','edit','edited',
            'pending','assigned','under_review'
        ) NOT NULL DEFAULT 'pending'");
    }
};
