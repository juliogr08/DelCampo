<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Make fecha_vencimiento nullable for store products
        DB::statement('ALTER TABLE productos MODIFY fecha_vencimiento DATE NULL');
    }

    public function down()
    {
        // Keep as nullable - safer
    }
};


