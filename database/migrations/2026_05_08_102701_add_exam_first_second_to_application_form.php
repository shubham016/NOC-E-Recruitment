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
        Schema::table('application_form', function (Blueprint $table) {
            $table->string('exam_date_first')->nullable()->after('exam_date');
            $table->string('exam_time_first')->nullable()->after('exam_date_first');
            $table->string('exam_date_second')->nullable()->after('exam_time_first');
            $table->string('exam_time_second')->nullable()->after('exam_date_second');
        });
    }

    public function down(): void
    {
        Schema::table('application_form', function (Blueprint $table) {
            $table->dropColumn(['exam_date_first', 'exam_time_first', 'exam_date_second', 'exam_time_second']);
        });
    }
};
