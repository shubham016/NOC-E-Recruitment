<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_form', function (Blueprint $table) {
            $table->string('alternate_phone_number', 20)
                  ->nullable()
                  ->after('department'); 
        });
    }

    public function down(): void
    {
        Schema::table('application_form', function (Blueprint $table) {
            $table->dropColumn('alternate_phone_number');
        });
    }
};