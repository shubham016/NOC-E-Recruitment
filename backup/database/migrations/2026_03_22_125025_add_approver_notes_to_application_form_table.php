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
            $table->text('approver_notes')->nullable()->after('approver_id');
            $table->timestamp('approved_at')->nullable()->after('approver_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_form', function (Blueprint $table) {
            $table->dropColumn(['approver_notes', 'approved_at']);
        });
    }
};
