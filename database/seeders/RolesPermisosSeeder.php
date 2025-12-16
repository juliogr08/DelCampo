<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesPermisosSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar caché de permisos
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ==========================================
        // CREAR PERMISOS
        // ==========================================
        
        // Permisos de productos
        Permission::firstOrCreate(['name' => 'ver productos']);
        Permission::firstOrCreate(['name' => 'crear productos']);
        Permission::firstOrCreate(['name' => 'editar productos']);
        Permission::firstOrCreate(['name' => 'eliminar productos']);
        
        // Permisos de pedidos
        Permission::firstOrCreate(['name' => 'ver pedidos']);
        Permission::firstOrCreate(['name' => 'gestionar pedidos']);
        Permission::firstOrCreate(['name' => 'cambiar estado pedidos']);
        
        // Permisos de almacenes
        Permission::firstOrCreate(['name' => 'ver almacenes']);
        Permission::firstOrCreate(['name' => 'gestionar almacenes']);
        
        // Permisos de clientes
        Permission::firstOrCreate(['name' => 'ver clientes']);
        
        // Permisos de reportes
        Permission::firstOrCreate(['name' => 'ver reportes']);
        Permission::firstOrCreate(['name' => 'exportar reportes']);
        
        // Permisos de solicitudes
        Permission::firstOrCreate(['name' => 'ver solicitudes']);
        Permission::firstOrCreate(['name' => 'gestionar solicitudes']);
        
        // Permisos del dashboard
        Permission::firstOrCreate(['name' => 'ver dashboard']);

        // ==========================================
        // CREAR ROLES
        // ==========================================
        
        // Rol Admin - Todos los permisos
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());
        
        // Rol Cliente - Permisos limitados
        $clienteRole = Role::firstOrCreate(['name' => 'cliente']);
        $clienteRole->givePermissionTo([
            'ver productos',
        ]);

        // ==========================================
        // ASIGNAR ROLES A USUARIOS EXISTENTES
        // ==========================================
        
        // Buscar usuarios por su campo 'rol' y asignarles el rol de Spatie
        $admins = User::where('rol', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->assignRole('admin');
        }
        
        $clientes = User::where('rol', 'cliente')->get();
        foreach ($clientes as $cliente) {
            $cliente->assignRole('cliente');
        }

        $this->command->info('✓ Roles y permisos creados correctamente');
        $this->command->info('  - Rol admin: ' . $admins->count() . ' usuarios');
        $this->command->info('  - Rol cliente: ' . $clientes->count() . ' usuarios');
    }
}
