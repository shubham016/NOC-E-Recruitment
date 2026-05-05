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
            $table->enum('manual_priority', ['critical', 'high', 'medium', 'low', 'normal'])->nullable()->after('status');
            $table->text('priority_note')->nullable()->after('manual_priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_form', function (Blueprint $table) {
            $table->dropColumn(['manual_priority', 'priority_note']);
        });
    }
};
