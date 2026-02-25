<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('results');

        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained('candidates')->onDelete('cascade');
            $table->foreignId('application_id')->constrained('application_form')->onDelete('cascade');
            $table->string('full_name');
            $table->string('citizenship_number')->nullable();
            $table->string('roll_number')->nullable();
            $table->decimal('marks', 8, 2)->nullable();
            $table->enum('status', ['pass', 'fail', 'pending', 'withheld'])->default('pending');
            $table->string('rank')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
