<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('application_form', 'reviewer_id')) {
            Schema::table('application_form', function (Blueprint $table) {
                $table->unsignedBigInteger('reviewer_id')->nullable()->after('id');
                $table->foreign('reviewer_id')
                    ->references('id')
                    ->on('reviewers')
                    ->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('application_form', 'reviewer_id')) {
            Schema::table('application_form', function (Blueprint $table) {
                $table->dropForeign(['reviewer_id']);
                $table->dropColumn('reviewer_id');
            });
        }
    }
};
