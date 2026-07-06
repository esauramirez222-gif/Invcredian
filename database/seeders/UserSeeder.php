<?php

namespace database\seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creamos el primer administrador del sistema
        User::create([
            'name' => 'Administrador Sistema',
            'email' => 'admin@inventario.com',
            'password' => Hash::make('admin12345'), // Contraseña segura temporal
        ]);
    }
}