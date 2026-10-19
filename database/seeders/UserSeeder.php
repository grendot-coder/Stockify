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
                'phone' => '081234567890',
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
                'phone' => '081234567891',
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
                'phone' => '081234567892',
                'is_active' => true,
            ]
        );
    }
}
