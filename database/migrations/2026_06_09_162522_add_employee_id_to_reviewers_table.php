<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Column may already exist from partial migration, add only if missing
        if (!Schema::hasColumn('reviewers', 'employee_id')) {
            Schema::table('reviewers', function (Blueprint $table) {
                $table->string('employee_id', 50)->nullable()->after('id');
            });
        }

        // Set employee_id for existing reviewers based on their id
        $reviewers = DB::table('reviewers')->whereNull('employee_id')->orWhere('employee_id', '')->get();
        foreach ($reviewers as $reviewer) {
            DB::table('reviewers')->where('id', $reviewer->id)->update([
                'employee_id' => 'REV-' . str_pad($reviewer->id, 4, '0', STR_PAD_LEFT),
            ]);
        }

        // Now make it unique and not nullable
        Schema::table('reviewers', function (Blueprint $table) {
            $table->string('employee_id', 50)->nullable(false)->unique()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviewers', function (Blueprint $table) {
            $table->dropColumn('employee_id');
        });
    }
};
