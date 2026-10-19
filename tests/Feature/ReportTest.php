<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\Supplier;
use App\Models\User;
use Tests\TestCase;

class ReportTest extends TestCase
{
    protected $adminUser;

    protected $managerUser;

    protected $staffUser;

    protected $category;

    protected $supplier;

    protected $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::where('role', 'admin')->first()
            ?? User::factory()->create(['role' => 'admin']);

        $this->managerUser = User::where('role', 'manager')->first()
            ?? User::factory()->create(['role' => 'manager']);

        $this->staffUser = User::where('role', 'staff')->first()
            ?? User::factory()->create(['role' => 'staff']);

        $this->category = Category::first() ?? Category::create(['name' => 'Kategori Laporan '.uniqid()]);
        $this->supplier = Supplier::first() ?? Supplier::create(['name' => 'Supplier Laporan '.uniqid()]);

        $this->product = Product::create([
            'category_id' => $this->category->id,
            'supplier_id' => $this->supplier->id,
            'sku' => 'REP-'.uniqid().'-'.mt_rand(100, 999),
            'name' => 'Produk Laporan '.rand(100, 999),
            'purchase_price' => 20000,
            'selling_price' => 30000,
            'stock' => 75,
            'min_stock' => 15,
        ]);

        StockTransaction::create([
            'user_id' => $this->staffUser->id,
            'product_id' => $this->product->id,
            'supplier_id' => $this->supplier->id,
            'invoice_number' => 'INV-REP-'.uniqid().'-'.mt_rand(100, 999),
            'type' => 'in',
            'quantity' => 25,
            'status' => 'confirmed',
            'confirmed_by' => $this->adminUser->id,
            'notes' => 'Testing transaksi laporan',
        ]);
    }

    /**
     * Admin dapat melihat halaman Laporan Stok.
     */
    public function test_admin_can_access_stock_report(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/reports/stock');
        $response->assertStatus(200);
        $response->assertSeeText('Laporan Persediaan & Valuasi Stok', false);
    }

    /**
     * Manager dapat melihat halaman Laporan Stok.
     */
    public function test_manager_can_access_stock_report(): void
    {
        $response = $this->actingAs($this->managerUser)->get('/reports/stock');
        $response->assertStatus(200);
        $response->assertSeeText('Laporan Persediaan & Valuasi Stok', false);
    }

    /**
     * Staff tidak memiliki akses ke Laporan Stok (Role restricted).
     */
    public function test_staff_cannot_access_stock_report(): void
    {
        $response = $this->actingAs($this->staffUser)->get('/reports/stock');
        $this->assertTrue(in_array($response->status(), [403, 302]));
    }

    /**
     * Admin dapat melihat halaman Laporan Mutasi Transaksi.
     */
    public function test_admin_can_access_transactions_report(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/reports/transactions');
        $response->assertStatus(200);
        $response->assertSee('Laporan Riwayat Mutasi Barang');
    }

    /**
     * Manager dapat melihat halaman Laporan Mutasi Transaksi.
     */
    public function test_manager_can_access_transactions_report(): void
    {
        $response = $this->actingAs($this->managerUser)->get('/reports/transactions');
        $response->assertStatus(200);
        $response->assertSee('Laporan Riwayat Mutasi Barang');
    }

    /**
     * Staff tidak memiliki akses ke Laporan Transaksi.
     */
    public function test_staff_cannot_access_transactions_report(): void
    {
        $response = $this->actingAs($this->staffUser)->get('/reports/transactions');
        $this->assertTrue(in_array($response->status(), [403, 302]));
    }

    /**
     * Ekspor Laporan Stok ke format CSV.
     */
    public function test_export_stock_report_csv(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/reports/stock?export=csv');
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('Content-Disposition') ?? '', 'laporan-stok-stockify-'));
    }

    /**
     * Tampilan Cetak / PDF Laporan Stok.
     */
    public function test_export_stock_report_print(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/reports/stock?export=print');
        $response->assertStatus(200);
        $response->assertSeeText('LAPORAN PERSEDIAAN & AUDIT NILAI STOK GUDANG', false);
    }

    /**
     * Ekspor Laporan Transaksi ke format CSV.
     */
    public function test_export_transactions_report_csv(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/reports/transactions?export=csv');
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('Content-Disposition') ?? '', 'laporan-mutasi-stockify-'));
    }

    /**
     * Tampilan Cetak / PDF Laporan Transaksi.
     */
    public function test_export_transactions_report_print(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/reports/transactions?export=print');
        $response->assertStatus(200);
        $response->assertSeeText('LAPORAN RIWAYAT MUTASI TRANSAKSI GUDANG', false);
    }

    /**
     * Dashboard memuat data grafik analitik dan widget sesuai role.
     */
    public function test_dashboard_renders_role_specific_content_and_charts(): void
    {
        // Admin
        $resAdmin = $this->actingAs($this->adminUser)->get('/dashboard');
        $resAdmin->assertStatus(200);
        $resAdmin->assertSee('mutation-trend-chart');
        $resAdmin->assertSee('Administrator Sistem');

        // Manager
        $resManager = $this->actingAs($this->managerUser)->get('/dashboard');
        $resManager->assertStatus(200);
        $resManager->assertSee('Manajer Gudang');

        // Staff
        $resStaff = $this->actingAs($this->staffUser)->get('/dashboard');
        $resStaff->assertStatus(200);
        $resStaff->assertSee('Staff Operasional');
        $resStaff->assertSee('Daftar Tugas Operasional Staff Gudang');
    }
}
