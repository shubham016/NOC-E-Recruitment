<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('application_form', function (Blueprint $table) {
            // Drop old columns
            if (Schema::hasColumn('application_form', 'educational_certificates')) {
                $table->dropColumn('educational_certificates');
            }

            if (Schema::hasColumn('application_form', 'resume_cv')) {
                $table->dropColumn('resume_cv');
            }

            // Add new columns
            $table->string('transcript')->nullable();
            $table->string('character')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('application_form', function (Blueprint $table) {
            // Re-add old columns
            $table->string('educational_certificates')->nullable();
            $table->string('resume_cv')->nullable();

            // Drop new columns
            $table->dropColumn(['transcript', 'character']);
        });
    }
};
