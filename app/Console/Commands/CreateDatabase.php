<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PDO;
use PDOException;

class CreateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea la base de datos automáticamente si no existe';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $database = env('DB_DATABASE');
        $host = env('DB_HOST', '127.0.0.1');
        $port = env('DB_PORT', '3306');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');

        if (empty($database)) {
            $this->error('❌ No se ha configurado DB_DATABASE en el archivo .env');
            return Command::FAILURE;
        }

        try {
            // Conectar a MySQL sin especificar la base de datos
            $pdo = new PDO(
                "mysql:host={$host};port={$port}",
                $username,
                $password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            // Verificar si la base de datos ya existe
            $stmt = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$database}'");
            $exists = $stmt->fetch();

            if ($exists) {
                $this->info("✅ La base de datos '{$database}' ya existe.");
                return Command::SUCCESS;
            }

            // Crear la base de datos
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            $this->info("✅ Base de datos '{$database}' creada exitosamente!");

            return Command::SUCCESS;

        } catch (PDOException $e) {
            $this->error("❌ Error al crear la base de datos: " . $e->getMessage());
            $this->warn("💡 Asegúrate de que:");
            $this->warn("   - MySQL esté corriendo");
            $this->warn("   - Las credenciales en .env sean correctas");
            $this->warn("   - El usuario tenga permisos para crear bases de datos");
            return Command::FAILURE;
        }
    }
}
