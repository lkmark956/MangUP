<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * AdminUserSeeder
 * 
 * Este seeder crea un usuario administrador de prueba.
 * 
 * Credenciales por defecto:
 * - Email: admin@mangup.com
 * - Password: admin123
 * 
 * IMPORTANTE: En producción, cambiar estas credenciales
 */
class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar si ya existe el admin para no duplicar
        if (!User::where('email', 'admin@mangup.com')->exists()) {
            User::create([
                'name' => 'Administrador',
                'email' => 'admin@mangup.com',
                'password' => Hash::make('admin123'),
                'is_admin' => true, // ← Este campo lo hace admin
            ]);
            
            $this->command->info('✓ Usuario admin creado: admin@mangup.com / admin123');
        } else {
            $this->command->info('→ El usuario admin ya existe');
        }
    }
}
