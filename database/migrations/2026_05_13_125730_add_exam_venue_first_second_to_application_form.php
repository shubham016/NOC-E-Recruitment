<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_form', function (Blueprint $table) {
            $table->text('exam_venue_first')->nullable()->after('exam_time_first');
            $table->text('exam_venue_second')->nullable()->after('exam_time_second');
        });
    }

    public function down(): void
    {
        Schema::table('application_form', function (Blueprint $table) {
            $table->dropColumn(['exam_venue_first', 'exam_venue_second']);
        });
    }
};
