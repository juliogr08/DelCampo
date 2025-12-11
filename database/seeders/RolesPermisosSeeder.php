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
        Permission::create(['name' => 'ver productos']);
        Permission::create(['name' => 'crear productos']);
        Permission::create(['name' => 'editar productos']);
        Permission::create(['name' => 'eliminar productos']);
        
        // Permisos de pedidos
        Permission::create(['name' => 'ver pedidos']);
        Permission::create(['name' => 'gestionar pedidos']);
        Permission::create(['name' => 'cambiar estado pedidos']);
        
        // Permisos de almacenes
        Permission::create(['name' => 'ver almacenes']);
        Permission::create(['name' => 'gestionar almacenes']);
        
        // Permisos de clientes
        Permission::create(['name' => 'ver clientes']);
        
        // Permisos de reportes
        Permission::create(['name' => 'ver reportes']);
        Permission::create(['name' => 'exportar reportes']);
        
        // Permisos de solicitudes
        Permission::create(['name' => 'ver solicitudes']);
        Permission::create(['name' => 'gestionar solicitudes']);
        
        // Permisos del dashboard
        Permission::create(['name' => 'ver dashboard']);

        // ==========================================
        // CREAR ROLES
        // ==========================================
        
        // Rol Admin - Todos los permisos
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());
        
        // Rol Cliente - Permisos limitados
        $clienteRole = Role::create(['name' => 'cliente']);
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
