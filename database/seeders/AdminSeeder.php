<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@papeleria.com'],
            [
                'name' => 'Admin',
                'cedula' => '1234567890',
                'password' => Hash::make('admin'),
                'idrol' => 1,
                'intentos_fallidos' => 0,
            ]
        );
    }
}