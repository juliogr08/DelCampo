<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Make codigo_barras nullable for store products
        DB::statement('ALTER TABLE productos MODIFY codigo_barras VARCHAR(100) NULL');
    }

    public function down()
    {
        DB::statement('ALTER TABLE productos MODIFY codigo_barras VARCHAR(100) NOT NULL');
    }
};
