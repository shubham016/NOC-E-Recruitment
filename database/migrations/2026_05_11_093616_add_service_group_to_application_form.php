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
            $table->string('admit_card_service_group')->nullable()->after('post_title');
        });
    }

    public function down(): void
    {
        Schema::table('application_form', function (Blueprint $table) {
            $table->dropColumn('admit_card_service_group');
        });
    }
};
