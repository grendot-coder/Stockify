<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockOpname;
use App\Models\StockTransaction;
use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard utama terpadu berdasarkan role.
     */
    public function index()
    {
        $user = Auth::user();

        // 1. Metrik Umum
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();
        $totalUsers = User::count();

        // Total kuantitas stok & total valuasi aset gudang
        $totalStockQty = Product::sum('stock');
        $totalInventoryValue = Product::select(DB::raw('SUM(stock * purchase_price) as total'))->value('total') ?? 0;

        // Produk dengan stok kritis/di bawah batas minimum
        $lowStockProducts = Product::whereColumn('stock', '<=', 'min_stock')->with('category')->get();
        $lowStockCount = $lowStockProducts->count();

        // Transaksi hari ini
        $transactionsToday = StockTransaction::whereDate('created_at', today())->get();
        $inboundTodayCount = $transactionsToday->where('type', 'in')->count();
        $outboundTodayCount = $transactionsToday->where('type', 'out')->count();

        // Transaksi yang butuh verifikasi (pending)
        $pendingTransactions = StockTransaction::where('status', 'pending')
            ->with(['product', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // Antrean Tugas Khusus Staff: Barang Masuk & Barang Keluar Pending
        $pendingInbounds = StockTransaction::where('type', 'in')
            ->where('status', 'pending')
            ->with(['product.category', 'supplier', 'user'])
            ->latest()
            ->get();

        $pendingOutbounds = StockTransaction::where('type', 'out')
            ->where('status', 'pending')
            ->with(['product.category', 'user'])
            ->latest()
            ->get();

        // Log Aktivitas terbaru
        $recentActivities = ActivityLog::with('user')->latest()->take(6)->get();

        // Transaksi terbaru
        $recentTransactions = StockTransaction::with(['product', 'user', 'supplier'])->latest()->take(5)->get();

        // 2. Data Grafik Analitik (Fase 7)
        // A. Tren Mutasi 7 Hari Terakhir (Inbound vs Outbound)
        $chartDates = [];
        $chartInbound = [];
        $chartOutbound = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dateStr = $date->toDateString();
            $chartDates[] = $date->translatedFormat('d M');

            $inQty = StockTransaction::whereDate('created_at', $dateStr)
                ->where('type', 'in')
                ->where('status', 'confirmed')
                ->sum('quantity');

            $outQty = StockTransaction::whereDate('created_at', $dateStr)
                ->where('type', 'out')
                ->where('status', 'confirmed')
                ->sum('quantity');

            $chartInbound[] = (int) $inQty;
            $chartOutbound[] = (int) $outQty;
        }

        // B. Komposisi Persediaan per Kategori (Donut Chart)
        $categoryBreakdown = Category::withCount(['products as total_stock' => function ($q) {
            $q->select(DB::raw('COALESCE(SUM(stock), 0)'));
        }])
            ->orderByDesc('total_stock')
            ->take(6)
            ->get();

        $catLabels = [];
        $catSeries = [];
        foreach ($categoryBreakdown as $cat) {
            $catLabels[] = $cat->name;
            $catSeries[] = (int) $cat->total_stock;
        }

        // C. Opname Discrepancy Overview
        $totalOpnames = StockOpname::count();
        $matchedOpnames = StockOpname::where('difference', 0)->count();

        return view('pages.dashboard', compact(
            'user',
            'totalProducts',
            'totalCategories',
            'totalSuppliers',
            'totalUsers',
            'totalStockQty',
            'totalInventoryValue',
            'lowStockProducts',
            'lowStockCount',
            'inboundTodayCount',
            'outboundTodayCount',
            'pendingTransactions',
            'pendingInbounds',
            'pendingOutbounds',
            'recentActivities',
            'recentTransactions',
            'chartDates',
            'chartInbound',
            'chartOutbound',
            'catLabels',
            'catSeries',
            'totalOpnames',
            'matchedOpnames'
        ));
    }
}
