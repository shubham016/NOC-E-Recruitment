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
        Schema::table('candidate_registration', function (Blueprint $table) {
            $table->integer('age')->nullable();
            // Or with specific options:
            // $table->integer('age')->default(0);
            // $table->unsignedTinyInteger('age')->nullable(); // for ages 0-255
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('candidate_registration', function (Blueprint $table) {
            $table->dropColumn('age');
        });
    }
};