<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_form', function (Blueprint $table) {
            // Change graduation_year_english to integer
            $table->integer('graduation_year_english')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('application_form', function (Blueprint $table) {
            // Revert back to string if needed
            $table->string('graduation_year_english')->nullable()->change();
        });
    }
};