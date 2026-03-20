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
        Schema::dropIfExists('candidate_registration');
        
        Schema::create('candidate_registration', function (Blueprint $table) {
            $table->id();
            
            $table->string('name')->nullable(); 
            $table->string('gender')->nullable();
            $table->string('date_of_birth_bs')->nullable(); 
            $table->string('citizenship_number')->nullable(); 
            $table->string('citizenship_issue_distric')->nullable();
            $table->string('citizenship_issue_date_bs')->nullable();
            $table->string('password')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_registration');
    }
};

