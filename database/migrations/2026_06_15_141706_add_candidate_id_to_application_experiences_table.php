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
        Schema::table('application_experiences', function (Blueprint $table) {
            $table->unsignedBigInteger('candidate_id')->nullable()->after('id');
            $table->unsignedBigInteger('application_form_id')->nullable()->change();
            
            $table->foreign('candidate_id')
                ->references('id')
                ->on('candidate_registration')
                ->onDelete('cascade');
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('application_experiences', function (Blueprint $table) {
            //
        });
    }
};
