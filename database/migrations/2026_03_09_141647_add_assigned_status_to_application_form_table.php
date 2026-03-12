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
        // Add 'assigned' to status enum
        \DB::statement("ALTER TABLE `application_form` MODIFY COLUMN `status` ENUM('draft', 'pending', 'assigned', 'approved', 'rejected', 'shortlisted', 'selected') DEFAULT 'draft'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'assigned' from status enum
        \DB::statement("ALTER TABLE `application_form` MODIFY COLUMN `status` ENUM('draft', 'pending', 'approved', 'rejected', 'shortlisted', 'selected') DEFAULT 'draft'");
    }
};
