<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Add 'tienda' to the rol enum in users table
        DB::statement("ALTER TABLE users MODIFY COLUMN rol ENUM('admin', 'cliente', 'tienda') DEFAULT 'cliente'");
    }

    public function down()
    {
        // Revert to original enum (WARNING: this will fail if there are 'tienda' values)
        DB::statement("ALTER TABLE users MODIFY COLUMN rol ENUM('admin', 'cliente') DEFAULT 'cliente'");
    }
};
