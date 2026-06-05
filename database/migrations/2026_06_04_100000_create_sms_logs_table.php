<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('candidate_id')->nullable();
            $table->string('phone', 20);
            $table->text('message');
            $table->integer('response_code')->nullable();
            $table->string('response_message')->nullable();
            $table->timestamps();

            $table->index('admin_id');
            $table->index('candidate_id');
            $table->index('phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
    }
};
