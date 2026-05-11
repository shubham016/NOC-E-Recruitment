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
        Schema::create('application_status_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_form_id');
            $table->string('stage_name');
            $table->string('done_by');
            $table->string('done_by_type');
            $table->unsignedBigInteger('done_by_id');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('application_form_id')
                  ->references('id')
                  ->on('application_form')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_status_histories');
    }
};
