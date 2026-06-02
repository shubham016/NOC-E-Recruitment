<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidate_registration', function (Blueprint $table) {
            if (!Schema::hasColumn('candidate_registration', 'remember_token')) {
                $table->rememberToken();
            }
        });
    }

    public function down(): void
    {
        Schema::table('candidate_registration', function (Blueprint $table) {
            $table->dropColumn('remember_token');
        });
    }
};
