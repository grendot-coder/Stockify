<?php

namespace Database\Seeders;

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
        // 1. Admin
        User::updateOrCreate(
            ['email' => 'admin@stockify.test'],
            [
                'name' => 'Administrator Stockify',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // 2. Manajer Gudang
        User::updateOrCreate(
            ['email' => 'manager@stockify.test'],
            [
                'name' => 'Budi Santoso (Manajer)',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'is_active' => true,
            ]
        );

        // 3. Staff Gudang
        User::updateOrCreate(
            ['email' => 'staff@stockify.test'],
            [
                'name' => 'Siti Rahma (Staff)',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'is_active' => true,
            ]
        );
    }
}
