<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('application_form', 'alternate_phone_number')) {
            DB::statement('ALTER TABLE `application_form` ADD COLUMN `alternate_phone_number` VARCHAR(20) NULL');
            return;
        }

        DB::statement('ALTER TABLE `application_form` MODIFY COLUMN `alternate_phone_number` VARCHAR(20) NULL');
    }

    public function down(): void
    {
        if (Schema::hasColumn('application_form', 'alternate_phone_number')) {
            DB::statement('ALTER TABLE `application_form` MODIFY COLUMN `alternate_phone_number` BIGINT NULL');
        }
    }
};
