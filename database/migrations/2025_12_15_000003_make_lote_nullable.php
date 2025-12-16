<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Make lote nullable for store products 
        DB::statement('ALTER TABLE productos MODIFY lote VARCHAR(100) NULL');
    }

    public function down()
    {
        DB::statement('ALTER TABLE productos MODIFY lote VARCHAR(100) NOT NULL');
    }
};
