<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_form', function (Blueprint $table) {
            $table->timestamp('reviewed_at')->nullable()->after('reviewer_id');
        });
    }

    public function down(): void
    {
        Schema::table('application_form', function (Blueprint $table) {
            $table->dropColumn('reviewed_at');
        });
    }
};