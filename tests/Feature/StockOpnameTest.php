<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockOpname;
use App\Models\Supplier;
use App\Models\User;
use Tests\TestCase;

class StockOpnameTest extends TestCase
{
    protected $adminUser;

    protected $managerUser;

    protected $staffUser;

    protected $testProduct;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::where('role', 'admin')->first()
            ?? User::factory()->create(['role' => 'admin']);

        $this->managerUser = User::where('role', 'manager')->first()
            ?? User::factory()->create(['role' => 'manager']);

        $this->staffUser = User::where('role', 'staff')->first()
            ?? User::factory()->create(['role' => 'staff']);

        $category = Category::first() ?? Category::create(['name' => 'Kategori Opname '.uniqid()]);
        $supplier = Supplier::first() ?? Supplier::create(['name' => 'Supplier Opname '.uniqid()]);

        $this->testProduct = Product::create([
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
            'sku' => 'OPN-SKU-'.uniqid().'-'.mt_rand(100, 999),
            'name' => 'Produk Uji Opname '.rand(100, 999),
            'purchase_price' => 25000,
            'selling_price' => 35000,
            'stock' => 50,
            'min_stock' => 10,
        ]);
    }

    /**
     * Test pengguna terautentikasi dapat melihat daftar riwayat stock opname.
     */
    public function test_user_can_view_opnames_index(): void
    {
        $response = $this->actingAs($this->staffUser)->get('/opnames');
        $response->assertStatus(200);
        $response->assertSee('Stock Opname Gudang');
    }

    /**
     * Test pengguna dapat mengakses formulir perhitungan fisik opname.
     */
    public function test_user_can_access_create_opname_form(): void
    {
        $response = $this->actingAs($this->staffUser)->get('/opnames/create');
        $response->assertStatus(200);
        $response->assertSee('Formulir Stock Opname');
        $response->assertSee($this->testProduct->sku);
    }

    /**
     * Test input stock opname menghitung selisih dan mencatat activity log.
     */
    public function test_store_opname_records_difference_and_activity_log(): void
    {
        $systemStock = $this->testProduct->stock; // 50
        $physicalStock = 45; // Defisit 5

        $response = $this->actingAs($this->staffUser)->post('/opnames', [
            'product_id' => $this->testProduct->id,
            'physical_stock' => $physicalStock,
            'opname_date' => date('Y-m-d'),
            'notes' => 'Terdapat 5 unit rusak saat diperiksa di rak A.',
        ]);

        $response->assertRedirect();

        $opname = StockOpname::where('product_id', $this->testProduct->id)->latest('id')->first();
        $this->assertNotNull($opname);
        $this->assertEquals($systemStock, $opname->system_stock);
        $this->assertEquals($physicalStock, $opname->physical_stock);
        $this->assertEquals(-5, $opname->difference);

        // Verifikasi activity log tercatat
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->staffUser->id,
            'action' => 'STOCK_OPNAME',
        ]);
    }

    /**
     * Test admin atau manager dapat menyesuaikan stok sistem secara langsung dengan opname.
     */
    public function test_admin_can_adjust_system_stock_with_opname(): void
    {
        $newPhysicalStock = 60; // Surplus +10 dari 50

        $response = $this->actingAs($this->adminUser)->post('/opnames', [
            'product_id' => $this->testProduct->id,
            'physical_stock' => $newPhysicalStock,
            'opname_date' => date('Y-m-d'),
            'notes' => 'Barang baru ditemukan di kardus belum dihitung.',
            'adjust_stock' => '1',
        ]);

        $response->assertRedirect();

        // Stok produk harus tersinkronisasi menjadi 60
        $this->testProduct->refresh();
        $this->assertEquals($newPhysicalStock, $this->testProduct->stock);
    }

    /**
     * Test staff tidak dapat mengubah/menyesuaikan stok sistem secara langsung.
     */
    public function test_staff_cannot_adjust_stock_silently(): void
    {
        $initialStock = $this->testProduct->stock;
        $manipulatedPhysical = $initialStock + 100;

        $response = $this->actingAs($this->staffUser)->post('/opnames', [
            'product_id' => $this->testProduct->id,
            'physical_stock' => $manipulatedPhysical,
            'opname_date' => date('Y-m-d'),
            'notes' => 'Percobaan staff adjust stok',
            'adjust_stock' => '1',
        ]);

        $response->assertRedirect();

        // Stok produk di database tetap awal karena staff tidak memiliki hak adjust
        $this->testProduct->refresh();
        $this->assertEquals($initialStock, $this->testProduct->stock);
    }

    /**
     * Test pengguna dapat melihat lembar bukti detail opname.
     */
    public function test_user_can_view_opname_detail(): void
    {
        $opname = StockOpname::create([
            'product_id' => $this->testProduct->id,
            'user_id' => $this->adminUser->id,
            'system_stock' => 50,
            'physical_stock' => 50,
            'difference' => 0,
            'notes' => 'Pemeriksaan rutin akhir pekan.',
            'opname_date' => date('Y-m-d'),
        ]);

        $response = $this->actingAs($this->staffUser)->get("/opnames/{$opname->id}");
        $response->assertStatus(200);
        $response->assertSee('Berita Acara Rekonsiliasi & Audit Stock Opname Gudang', false);
        $response->assertSee($this->testProduct->name);
    }

    /**
     * Test admin dapat melihat audit log aktivitas dan staff dilarang (403).
     */
    public function test_admin_can_view_activity_logs_and_staff_is_forbidden(): void
    {
        // Admin dapat melihat
        $adminResp = $this->actingAs($this->adminUser)->get('/logs');
        $adminResp->assertStatus(200);
        $adminResp->assertSee('Audit Log Aktivitas Sistem');

        // Staff dilarang (403)
        $staffResp = $this->actingAs($this->staffUser)->get('/logs');
        $staffResp->assertStatus(403);
    }
}
