<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $elektronik = Category::where('slug', 'elektronik-gadget')->first();
        $tekstil = Category::where('slug', 'pakaian-tekstil')->first();
        $pangan = Category::where('slug', 'makanan-minuman')->first();

        $supElektronik = Supplier::where('name', 'PT Sumber Mega Elektronik')->first();
        $supTekstil = Supplier::where('name', 'CV Tekstil Nusantara Prima')->first();
        $supPangan = Supplier::where('name', 'PT Pangan Makmur Abadi')->first();

        // 1. Mouse Wireless Logitech
        $p1 = Product::updateOrCreate(
            ['sku' => 'PRD-ELK-001'],
            [
                'category_id' => $elektronik?->id,
                'supplier_id' => $supElektronik?->id,
                'name' => 'Mouse Wireless Ergonomic M280',
                'description' => 'Mouse nirkabel presisi tinggi dengan baterai tahan hingga 18 bulan.',
                'purchase_price' => 120000,
                'selling_price' => 175000,
                'stock' => 45,
                'min_stock' => 10,
            ]
        );
        ProductAttribute::updateOrCreate(['product_id' => $p1->id, 'attribute_name' => 'Warna'], ['attribute_value' => 'Hitam Matte']);
        ProductAttribute::updateOrCreate(['product_id' => $p1->id, 'attribute_name' => 'Konektivitas'], ['attribute_value' => '2.4 GHz USB Dongle']);

        // 2. Keyboard Mechanical RGB
        $p2 = Product::updateOrCreate(
            ['sku' => 'PRD-ELK-002'],
            [
                'category_id' => $elektronik?->id,
                'supplier_id' => $supElektronik?->id,
                'name' => 'Mechanical Keyboard TKL Red Switch',
                'description' => 'Keyboard mekanikal compact tenkeyless dengan switch merah responsif.',
                'purchase_price' => 350000,
                'selling_price' => 499000,
                'stock' => 8, // Stok menipis (mendekati/di bawah min_stock)
                'min_stock' => 10,
            ]
        );
        ProductAttribute::updateOrCreate(['product_id' => $p2->id, 'attribute_name' => 'Switch Type'], ['attribute_value' => 'Outemu Red']);
        ProductAttribute::updateOrCreate(['product_id' => $p2->id, 'attribute_name' => 'Backlight'], ['attribute_value' => 'RGB 16 Juta Warna']);

        // 3. Kaos Polos Katun Combed
        $p3 = Product::updateOrCreate(
            ['sku' => 'PRD-TKS-001'],
            [
                'category_id' => $tekstil?->id,
                'supplier_id' => $supTekstil?->id,
                'name' => 'Kaos Polos Cotton Combed 30s',
                'description' => 'Kaos katun combed lembut dan adem untuk aktivitas sehari-hari.',
                'purchase_price' => 35000,
                'selling_price' => 60000,
                'stock' => 120,
                'min_stock' => 20,
            ]
        );
        ProductAttribute::updateOrCreate(['product_id' => $p3->id, 'attribute_name' => 'Bahan'], ['attribute_value' => '100% Cotton Combed 30s']);
        ProductAttribute::updateOrCreate(['product_id' => $p3->id, 'attribute_name' => 'Ukuran'], ['attribute_value' => 'L']);
        ProductAttribute::updateOrCreate(['product_id' => $p3->id, 'attribute_name' => 'Warna'], ['attribute_value' => 'Navy Blue']);

        // 4. Kopi Bubuk Arabika Robusta
        $p4 = Product::updateOrCreate(
            ['sku' => 'PRD-FNB-001'],
            [
                'category_id' => $pangan?->id,
                'supplier_id' => $supPangan?->id,
                'name' => 'Kopi House Blend 250g',
                'description' => 'Racikan biji kopi arabika gayo dan robusta dampit aromatik tinggi.',
                'purchase_price' => 45000,
                'selling_price' => 70000,
                'stock' => 3, // Di bawah min stock (warning)
                'min_stock' => 15,
            ]
        );
        ProductAttribute::updateOrCreate(['product_id' => $p4->id, 'attribute_name' => 'Berat Bersih'], ['attribute_value' => '250 gram']);
        ProductAttribute::updateOrCreate(['product_id' => $p4->id, 'attribute_name' => 'Roast Profile'], ['attribute_value' => 'Medium Dark']);
    }
}
