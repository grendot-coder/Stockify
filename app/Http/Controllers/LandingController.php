<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\Supplier;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Tampilkan landing page editorial bergaya Shopify dark theme.
     */
    public function index()
    {
        // Placeholder data portofolio video perusahaan (mudah diubah/update)
        $companyVideo = [
            'title' => 'Stockify Core Operations: Di Balik Distribusi Presisi',
            'duration' => '02:45 Menit',
            'resolution' => '4K Cinematic • 60 FPS',
            'director_note' => 'Dokumentasi fasilitas pergudangan modern dan automasi pelacakan alur mutasi barang Stockify di Asia Tenggara.',
            // Placeholder URL video (dapat diganti dengan URL MP4, YouTube embed, atau Cloudflare Stream)
            'video_url' => 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
            'poster_image' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1600&q=80',
        ];

        // Daftar perusahaan yang menggunakan Stockify (Updatable Placeholder)
        $clientCompanies = [
            [
                'name' => 'Nusantara Goods Co.',
                'industry' => 'Modern Lifestyle & Home Goods',
                'warehouses' => '4 Hub Regional',
                'units_tracked' => '240.000+ Unit',
                'location' => 'Jakarta & Surabaya',
                'monogram' => 'NG',
                'badge' => 'Retail Partner',
                'quote' => 'Stockify memangkas waktu rekonsiliasi opname bulanan kami dari 3 hari menjadi kurang dari 4 jam.',
            ],
            [
                'name' => 'Lumina Atelier',
                'industry' => 'Architectural Lighting & Ceramics',
                'warehouses' => '2 Studio Warehouse',
                'units_tracked' => '65.000+ Unit',
                'location' => 'Bandung',
                'monogram' => 'LA',
                'badge' => 'Design Studio',
                'quote' => 'Pelacakan multi-atribut membuat varian warna dan voltase produk kami tak pernah lagi tertukar saat pengiriman.',
            ],
            [
                'name' => 'Nordica Living',
                'industry' => 'Minimalist Furniture & Space',
                'warehouses' => '6 Gudang Terpadu',
                'units_tracked' => '1.150.000+ Unit',
                'location' => 'Tangerang & Semarang',
                'monogram' => 'NL',
                'badge' => 'Enterprise',
                'quote' => 'Verifikasi ganda antara staff fisik dan manajer gudang mengeliminasi selisih stok hingga 0.02%.',
            ],
            [
                'name' => 'Aethel Leatherworks',
                'industry' => 'Artisan Goods & Footwear',
                'warehouses' => '1 Central Atelier',
                'units_tracked' => '32.000+ Unit',
                'location' => 'Yogyakarta',
                'monogram' => 'AL',
                'badge' => 'Craft Heritage',
                'quote' => 'Tampilan antarmukanya sangat tenang, tidak berisik, dan staff gudang kami langsung mahir di hari pertama.',
            ],
            [
                'name' => 'Vesper Supply Chain',
                'industry' => 'Cold-Chain & Foodservice Logistik',
                'warehouses' => '9 Pusat Distribusi',
                'units_tracked' => '3.800.000+ Unit',
                'location' => 'Jabodetabek & Bali',
                'monogram' => 'VS',
                'badge' => 'Cold Chain',
                'quote' => 'Pencatatan nomor invoice dan riwayat audit memberi kepastian hukum mutlak saat serah terima barang.',
            ],
            [
                'name' => 'Sinar Kencana Logistik',
                'industry' => 'Distribusi FMCG Nasional',
                'warehouses' => '14 Depo Terintegrasi',
                'units_tracked' => '6.400.000+ Unit',
                'location' => 'Jawa & Sumatera',
                'monogram' => 'SK',
                'badge' => 'National Network',
                'quote' => 'Arsitektur sistem yang stabil dan tangguh, mampu menangani puluhan ribu mutasi barang harian.',
            ],
        ];

        // Metrik riil dari database (jika ada) atau fallback elegan
        $productCount = Product::count();
        $transactionCount = StockTransaction::count();
        $supplierCount = Supplier::count();

        return view('pages.landing', compact(
            'companyVideo',
            'clientCompanies',
            'productCount',
            'transactionCount',
            'supplierCount'
        ));
    }
}
