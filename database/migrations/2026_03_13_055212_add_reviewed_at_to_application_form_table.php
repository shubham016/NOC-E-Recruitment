<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('application_form', 'reviewed_at')) {
            Schema::table('application_form', function (Blueprint $table) {
                $table->timestamp('reviewed_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::table('application_form', function (Blueprint $table) {
            if (Schema::hasColumn('application_form', 'reviewed_at')) {
                $table->dropColumn('reviewed_at');
            }
        });
    }
};
