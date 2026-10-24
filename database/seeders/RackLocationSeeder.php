<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\RackLocation;
use Illuminate\Database\Seeder;

class RackLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zones = ['Zona A (Bahan Pokok)', 'Zona B (Kemasan & Kaleng)', 'Zona C (Suhu Dingin)', 'Zona D (Barang Cepat Habis)'];

        // Buat 32 Rack terstandarisasi Rack-01 s/d Rack-32
        for ($i = 1; $i <= 32; $i++) {
            $name = 'Rack-'.sprintf('%02d', $i);
            $zone = $zones[($i - 1) % count($zones)];
            $capacity = ($i % 3 === 0) ? 150 : 100;

            RackLocation::firstOrCreate(
                ['name' => $name],
                [
                    'capacity' => $capacity,
                    'zone' => $zone,
                    'description' => "Unit penyimpanan standar {$name} di {$zone}.",
                ]
            );
        }

        // Distribusikan beberapa produk ke rak untuk menghasilkan heatmap variatif
        $products = Product::all();
        $racks = RackLocation::all();

        if ($products->isNotEmpty() && $racks->isNotEmpty()) {
            foreach ($products as $index => $product) {
                // Sebarkan ke rak-rak awal (misal Rack-01 s/d Rack-15)
                $rackIndex = $index % 12;
                $product->update([
                    'rack_location_id' => $racks[$rackIndex]->id,
                ]);
            }
        }
    }
}
