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
        $table->string('university')->nullable()->after('institution_name');
    });
}

public function down(): void
{
    Schema::table('application_form', function (Blueprint $table) {
        $table->dropColumn('university');
    });
}
};
