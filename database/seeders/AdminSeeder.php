<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Crear usuario admin si no existe
        if (!User::where('email', 'admin@ecommerce.com')->exists()) {
            User::create([
                'name' => 'Administrador',
                'email' => 'admin@ecommerce.com',
                'password' => Hash::make('admin123'),
                'rol' => 'admin',
                'telefono' => '78000000',
                'ciudad' => 'Santa Cruz de la Sierra',
            ]);

            $this->command->info('✓ Usuario admin creado: admin@ecommerce.com / admin123');
        } else {
            $this->command->info('El usuario admin ya existe');
        }
    }
}
