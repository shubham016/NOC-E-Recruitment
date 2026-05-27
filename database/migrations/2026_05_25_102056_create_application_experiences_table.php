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
        Schema::create('application_experiences', function (Blueprint $table) {
            $table->id();
        $table->unsignedBigInteger('application_form_id');
        $table->tinyInteger('exp_number');
        $table->string('organization', 150)->nullable();
        $table->string('position', 150)->nullable();
        $table->string('start_date_bs', 20)->nullable();
        $table->string('start_date', 20)->nullable();
        $table->string('end_date_bs', 20)->nullable();
        $table->string('end_date', 20)->nullable();
        $table->decimal('years', 4, 1)->nullable();
        $table->string('document', 200)->nullable();
        $table->timestamps();

        $table->foreign('application_form_id')
              ->references('id')->on('application_form')
              ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_experiences');
    }
};
