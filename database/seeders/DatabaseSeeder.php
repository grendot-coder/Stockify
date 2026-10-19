<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            SupplierSeeder::class,
            ProductSeeder::class,
        ]);

        // Default System Settings
        Setting::updateOrCreate(['key' => 'app_name'], ['value' => 'Stockify']);
        Setting::updateOrCreate(['key' => 'company_name'], ['value' => 'PT Stockify Logistik Mandiri']);
        Setting::updateOrCreate(['key' => 'app_logo'], ['value' => null]);
        Setting::updateOrCreate(['key' => 'min_stock_alert'], ['value' => '5']);
    }
}
