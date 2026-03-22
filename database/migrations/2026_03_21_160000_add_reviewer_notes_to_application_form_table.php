<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('application_form', 'reviewer_notes')) {
            Schema::table('application_form', function (Blueprint $table) {
                $table->text('reviewer_notes')->nullable()->after('reviewer_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('application_form', 'reviewer_notes')) {
            Schema::table('application_form', function (Blueprint $table) {
                $table->dropColumn('reviewer_notes');
            });
        }
    }
};
