<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('application_form', function (Blueprint $table) {
            $table->string('applying_position', 255)->nullable()->after('job_posting_id');
        });
    }

    public function down()
    {
        Schema::table('application_form', function (Blueprint $table) {
            $table->dropColumn('applying_position');
        });
    }
};