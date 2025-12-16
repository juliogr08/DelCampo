<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Make almacen_id nullable for store-to-admin requests
        DB::statement('ALTER TABLE solicitudes_reposicion MODIFY almacen_id BIGINT UNSIGNED NULL');
    }

    public function down()
    {
        DB::statement('ALTER TABLE solicitudes_reposicion MODIFY almacen_id BIGINT UNSIGNED NOT NULL');
    }
};

