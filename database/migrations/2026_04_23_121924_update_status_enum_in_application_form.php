<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    DB::statement("ALTER TABLE application_form MODIFY COLUMN status ENUM('draft','submitted','approved','rejected','edit','edited','pending') DEFAULT 'draft'");
}

public function down()
{
    DB::statement("ALTER TABLE application_form MODIFY COLUMN status ENUM('draft','submitted','approved','rejected','edit') DEFAULT 'draft'");
}
};
