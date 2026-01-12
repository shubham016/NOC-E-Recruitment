<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('application_form', function (Blueprint $table) {
            $table->string('advertisement_no')->nullable()->after('citizenship_number');
            // OR if you prefer using ID:
            // $table->unsignedBigInteger('job_posting_id')->nullable()->after('citizenship_number');
            // $table->foreign('job_posting_id')->references('id')->on('job_postings')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('application_form', function (Blueprint $table) {
            $table->dropColumn('advertisement_no');
            // OR
            $table->dropForeign(['job_posting_id']);
            $table->dropColumn('job_posting_id');
        });
    }
};