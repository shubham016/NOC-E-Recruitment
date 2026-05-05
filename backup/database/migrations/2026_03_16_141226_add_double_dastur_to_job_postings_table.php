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
        Schema::table('job_postings', function (Blueprint $table) {
            $table->date('double_dastur_date')->nullable()->after('deadline_bs');
            $table->string('double_dastur_bs')->nullable()->after('double_dastur_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropColumn(['double_dastur_date', 'double_dastur_bs']);
        });
    }
};
