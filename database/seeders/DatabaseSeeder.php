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
        Setting::updateOrCreate(['key' => 'app_tagline'], ['value' => 'Warehouse & Supply Chain System']);
        Setting::updateOrCreate(['key' => 'warehouse_address'], ['value' => 'Kawasan Industri Pergudangan Terpadu Blok B-12, Jakarta']);
        Setting::updateOrCreate(['key' => 'warehouse_phone'], ['value' => '0812-3456-7890']);
        Setting::updateOrCreate(['key' => 'warehouse_email'], ['value' => 'operasional@stockify.id']);
        Setting::updateOrCreate(['key' => 'default_min_stock'], ['value' => '5']);
        Setting::updateOrCreate(['key' => 'min_stock_alert'], ['value' => '5']);
    }
}
