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
        if (!Schema::hasColumn('application_form', 'email')) {
            Schema::table('application_form', function (Blueprint $table) {
                $table->string('email')->nullable()->after('name_english');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_form', function (Blueprint $table) {
        $table->dropColumn('email');
     });
    }
};
