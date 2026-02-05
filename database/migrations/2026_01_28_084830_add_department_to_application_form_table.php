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
            $table->text('department')->nullable();
        });
    }

    public function down()
    {
        Schema::table('application_form', function (Blueprint $table) {
            $table->dropColumn(['department']);
        });
    }
};
