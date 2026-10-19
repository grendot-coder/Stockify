<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

// Landing Page Editorial & Portofolio Perusahaan
Route::get('/', [LandingController::class, 'index'])->name('landing');

// Autentikasi Pengguna (Guest Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Fitur Sistem (Wajib Login)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Internal API untuk Generate SKU Dinamis
    Route::get('/api/products/generate-sku', [ProductController::class, 'generateSku'])->name('products.generate-sku');

    // === Rute Hak Akses: Admin & Manager Gudang (Modifikasi Master Data) ===
    Route::middleware('role:admin,manager')->group(function () {
        // Master Kategori (Modal CRUD)
        Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);

        // Master Supplier (Modal CRUD)
        Route::resource('suppliers', SupplierController::class)->except(['create', 'show', 'edit']);

        // Kelola Produk (Tambah, Ubah, Hapus)
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

        // Transaksi Persetujuan (Khusus Admin & Manager)
        Route::post('/transactions/{id}/confirm', [StockTransactionController::class, 'confirm'])->name('transactions.confirm');
        Route::post('/transactions/{id}/reject', [StockTransactionController::class, 'reject'])->name('transactions.reject');

        // Modul Laporan & Ekspor (Fase 7)
        Route::get('/reports/stock', [ReportController::class, 'stock'])->name('reports.stock');
        Route::get('/reports/transactions', [ReportController::class, 'transactions'])->name('reports.transactions');
    });

    // === Rute Hak Akses: Semua Role (Admin, Manager, Staff - Melihat Master & Input Transaksi) ===
    Route::middleware('role:admin,manager,staff')->group(function () {
        // Data Produk (View Only untuk Staff)
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

        // Transaksi Barang Masuk & Keluar
        Route::get('/transactions', [StockTransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/inbound', [StockTransactionController::class, 'createInbound'])->name('transactions.inbound');
        Route::post('/transactions/inbound', [StockTransactionController::class, 'storeInbound'])->name('transactions.inbound.store');
        Route::get('/transactions/outbound', [StockTransactionController::class, 'createOutbound'])->name('transactions.outbound');
        Route::post('/transactions/outbound', [StockTransactionController::class, 'storeOutbound'])->name('transactions.outbound.store');
        Route::get('/transactions/{id}', [StockTransactionController::class, 'show'])->name('transactions.show');

        // Modul Stock Opname (Fase 6)
        Route::get('/opnames', [StockOpnameController::class, 'index'])->name('opnames.index');
        Route::get('/opnames/create', [StockOpnameController::class, 'create'])->name('opnames.create');
        Route::post('/opnames', [StockOpnameController::class, 'store'])->name('opnames.store');
        Route::get('/opnames/{id}', [StockOpnameController::class, 'show'])->name('opnames.show');
    });

    // === Rute Hak Akses: Khusus Admin ===
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', function () {
            return redirect()->route('dashboard')->with('info', 'Manajemen Pengguna akan diimplementasikan pada tahap penyempurnaan.');
        })->name('users.index');

        // Audit Log Aktivitas (Fase 6)
        Route::get('/logs', [ActivityLogController::class, 'index'])->name('logs.index');

        Route::get('/settings', function () {
            return redirect()->route('dashboard')->with('info', 'Pengaturan Aplikasi akan diimplementasikan pada tahap penyempurnaan.');
        })->name('settings.index');
    });
});
