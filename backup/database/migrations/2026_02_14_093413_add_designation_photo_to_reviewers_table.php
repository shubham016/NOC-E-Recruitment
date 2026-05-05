<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviewers', function (Blueprint $table) {
            if (!Schema::hasColumn('reviewers', 'designation')) {
                $table->string('designation')->nullable()->after('department');
            }
            if (!Schema::hasColumn('reviewers', 'photo')) {
                $table->string('photo')->nullable()->after('designation');
            }
        });
    }

    public function down(): void
    {
        Schema::table('reviewers', function (Blueprint $table) {
            $table->dropColumn(['designation', 'photo']);
        });
    }
};
