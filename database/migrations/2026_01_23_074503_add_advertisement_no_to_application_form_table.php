<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('application_form', function (Blueprint $table) {
            $table->string('advertisement_no')->nullable(); // adjust the type and constraints as needed
        });
    }

    public function down()
    {
        Schema::table('application_form', function (Blueprint $table) {
            $table->dropColumn('advertisement_no');
        });
    }
};