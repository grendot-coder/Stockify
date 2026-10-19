<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'PT Sumber Mega Elektronik',
                'email' => 'sales@sumbermega.co.id',
                'phone' => '021-5551234',
                'address' => 'Kawasan Industri Pulogadung Blok B No. 12, Jakarta Timur',
            ],
            [
                'name' => 'CV Tekstil Nusantara Prima',
                'email' => 'info@tekstilnusantara.com',
                'phone' => '022-8765432',
                'address' => 'Jl. Soekarno Hatta No. 450, Bandung',
            ],
            [
                'name' => 'PT Pangan Makmur Abadi',
                'email' => 'order@panganmakmur.com',
                'phone' => '031-7788990',
                'address' => 'Rungkut Industri III Kav. 15, Surabaya',
            ],
        ];

        foreach ($suppliers as $sup) {
            Supplier::updateOrCreate(
                ['name' => $sup['name']],
                $sup
            );
        }
    }
}
