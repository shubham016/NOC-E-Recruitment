<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('application_form', 'submitted_at')) {
            Schema::table('application_form', function (Blueprint $table) {
                $table->timestamp('submitted_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('application_form', 'submitted_at')) {
            Schema::table('application_form', function (Blueprint $table) {
                $table->dropColumn('submitted_at');
            });
        }
    }
};
