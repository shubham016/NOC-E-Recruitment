<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            if (!Schema::hasColumn('candidates', 'first_name')) {
                $table->string('first_name')->nullable()->after('id');
            }
            if (!Schema::hasColumn('candidates', 'middle_name')) {
                $table->string('middle_name')->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('candidates', 'last_name')) {
                $table->string('last_name')->nullable()->after('middle_name');
            }
            if (!Schema::hasColumn('candidates', 'username')) {
                $table->string('username')->nullable()->unique()->after('last_name');
            }
            if (!Schema::hasColumn('candidates', 'mobile_number')) {
                $table->string('mobile_number')->nullable()->after('email');
            }
            if (!Schema::hasColumn('candidates', 'city')) {
                $table->string('city')->nullable();
            }
            if (!Schema::hasColumn('candidates', 'state')) {
                $table->string('state')->nullable();
            }
            if (!Schema::hasColumn('candidates', 'country')) {
                $table->string('country')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('candidates', function (Blueprint $table) {
            $columns = ['first_name', 'middle_name', 'last_name', 'username', 'mobile_number', 'city', 'state', 'country'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('candidates', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
