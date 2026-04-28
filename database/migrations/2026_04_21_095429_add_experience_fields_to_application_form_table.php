<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('application_form', function (Blueprint $table) {

        // Experience 1
        $table->string('exp1_organization')->nullable();
        $table->string('exp1_position')->nullable();
        $table->date('exp1_start_date')->nullable();
        $table->date('exp1_end_date')->nullable();
        $table->decimal('exp1_years', 4, 1)->nullable();
        $table->string('exp1_document')->nullable();

        // Experience 2
        $table->string('exp2_organization')->nullable();
        $table->string('exp2_position')->nullable();
        $table->date('exp2_start_date')->nullable();
        $table->date('exp2_end_date')->nullable();
        $table->decimal('exp2_years', 4, 1)->nullable();
        $table->string('exp2_document')->nullable();

        // Experience 3
        $table->string('exp3_organization')->nullable();
        $table->string('exp3_position')->nullable();
        $table->date('exp3_start_date')->nullable();
        $table->date('exp3_end_date')->nullable();
        $table->decimal('exp3_years', 4, 1)->nullable();
        $table->string('exp3_document')->nullable();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('application_form', function (Blueprint $table) {
        $table->dropColumn([
            'exp1_organization','exp1_position','exp1_start_date','exp1_end_date','exp1_years','exp1_document',
            'exp2_organization','exp2_position','exp2_start_date','exp2_end_date','exp2_years','exp2_document',
            'exp3_organization','exp3_position','exp3_start_date','exp3_end_date','exp3_years','exp3_document'
        ]);
    });
}
};
