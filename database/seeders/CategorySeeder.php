<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Elektronik & Gadget',
                'description' => 'Perangkat elektronik, aksesoris, dan komponen komputer',
            ],
            [
                'name' => 'Pakaian & Tekstil',
                'description' => 'Pakaian pria, wanita, dan perlengkapan tekstil',
            ],
            [
                'name' => 'Makanan & Minuman',
                'description' => 'Produk makanan ringan, bahan pokok, dan minuman kemasan',
            ],
            [
                'name' => 'Alat Tulis & Kantor',
                'description' => 'Perlengkapan operasional kantor dan alat tulis sekolah',
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                ]
            );
        }
    }
}
