<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\Supplier;
use App\Models\User;
use Tests\TestCase;

class StockTransactionTest extends TestCase
{
    protected $adminUser;

    protected $staffUser;

    protected $testProduct;

    protected $testSupplier;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::where('role', 'admin')->first()
            ?? User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $this->staffUser = User::where('role', 'staff')->first()
            ?? User::factory()->create(['role' => 'staff', 'is_active' => true]);

        $category = Category::first() ?? Category::create(['name' => 'Kategori Demo '.uniqid()]);
        $this->testSupplier = Supplier::first() ?? Supplier::create(['name' => 'Supplier Demo '.uniqid(), 'phone' => '0812345678']);

        $this->testProduct = Product::create([
            'category_id' => $category->id,
            'supplier_id' => $this->testSupplier->id,
            'sku' => 'TEST-TRX-'.uniqid().'-'.mt_rand(100, 999),
            'name' => 'Produk Uji Transaksi '.rand(100, 999),
            'purchase_price' => 10000,
            'selling_price' => 15000,
            'stock' => 20,
            'min_stock' => 5,
        ]);
    }

    /**
     * Test pengguna terautentikasi dapat melihat daftar transaksi.
     */
    public function test_user_can_view_transactions_index(): void
    {
        $response = $this->actingAs($this->staffUser)->get('/transactions');
        $response->assertStatus(200);
        $response->assertSee('Manajemen Transaksi Stok');
    }

    /**
     * Test barang masuk terkonfirmasi menambah stok produk secara otomatis.
     */
    public function test_inbound_confirmed_increments_stock(): void
    {
        $initialStock = $this->testProduct->stock;
        $inboundQty = 15;

        $response = $this->actingAs($this->staffUser)->post('/transactions/inbound', [
            'product_id' => $this->testProduct->id,
            'supplier_id' => $this->testSupplier->id,
            'quantity' => $inboundQty,
            'status' => 'confirmed',
            'notes' => 'Penerimaan barang dari supplier PO-001',
        ]);

        $response->assertRedirect('/transactions');
        $response->assertSessionHas('success');

        $this->testProduct->refresh();
        $this->assertEquals($initialStock + $inboundQty, $this->testProduct->stock);

        $this->assertDatabaseHas('stock_transactions', [
            'product_id' => $this->testProduct->id,
            'type' => 'in',
            'quantity' => $inboundQty,
            'status' => 'confirmed',
        ]);
    }

    /**
     * Test barang masuk status pending tidak mengubah stok produk.
     */
    public function test_inbound_pending_does_not_change_stock_until_confirmed(): void
    {
        $initialStock = $this->testProduct->stock;
        $inboundQty = 25;

        $response = $this->actingAs($this->staffUser)->post('/transactions/inbound', [
            'product_id' => $this->testProduct->id,
            'supplier_id' => $this->testSupplier->id,
            'quantity' => $inboundQty,
            'status' => 'pending',
            'notes' => 'Menunggu verifikasi fisik',
        ]);

        $response->assertRedirect('/transactions');

        $this->testProduct->refresh();
        $this->assertEquals($initialStock, $this->testProduct->stock);

        $trx = StockTransaction::where('product_id', $this->testProduct->id)
            ->where('status', 'pending')
            ->latest('id')
            ->first();

        $this->assertNotNull($trx);

        // Sekarang Admin menyetujui transaksi pending tersebut
        $confirmResponse = $this->actingAs($this->adminUser)->post("/transactions/{$trx->id}/confirm");
        $confirmResponse->assertSessionHas('success');

        $this->testProduct->refresh();
        $this->assertEquals($initialStock + $inboundQty, $this->testProduct->stock);
    }

    /**
     * Test barang keluar mengurangi stok produk jika stok mencukupi.
     */
    public function test_outbound_confirmed_decrements_stock(): void
    {
        $initialStock = $this->testProduct->stock;
        $outboundQty = 8;

        $response = $this->actingAs($this->staffUser)->post('/transactions/outbound', [
            'product_id' => $this->testProduct->id,
            'quantity' => $outboundQty,
            'status' => 'confirmed',
            'notes' => 'Penjualan ke toko cabang A',
        ]);

        $response->assertRedirect('/transactions');
        $response->assertSessionHas('success');

        $this->testProduct->refresh();
        $this->assertEquals($initialStock - $outboundQty, $this->testProduct->stock);
    }

    /**
     * Test validasi menolak barang keluar jika kuantitas melebihi stok gudang.
     */
    public function test_outbound_fails_when_quantity_exceeds_stock(): void
    {
        $initialStock = $this->testProduct->stock;
        $excessQty = $initialStock + 50;

        $response = $this->actingAs($this->staffUser)->post('/transactions/outbound', [
            'product_id' => $this->testProduct->id,
            'quantity' => $excessQty,
            'status' => 'confirmed',
            'notes' => 'Permintaan melebihi kapasitas',
        ]);

        $response->assertSessionHasErrors(['quantity']);

        $this->testProduct->refresh();
        $this->assertEquals($initialStock, $this->testProduct->stock);
    }

    /**
     * Test penolakan transaksi pending oleh Admin dengan alasan.
     */
    public function test_admin_can_reject_pending_transaction(): void
    {
        $trx = StockTransaction::create([
            'invoice_number' => 'TRX-TEST-REJECT-'.uniqid().'-'.mt_rand(100, 999),
            'product_id' => $this->testProduct->id,
            'user_id' => $this->staffUser->id,
            'type' => 'in',
            'quantity' => 10,
            'status' => 'pending',
            'notes' => 'Barang masuk pending',
        ]);

        $response = $this->actingAs($this->adminUser)->post("/transactions/{$trx->id}/reject", [
            'reason' => 'Barang pecah di perjalanan',
        ]);

        $response->assertSessionHas('success');

        $trx->refresh();
        $this->assertEquals('rejected', $trx->status);
        $this->assertStringContainsString('Barang pecah di perjalanan', $trx->notes);
    }

    /**
     * Test staff dilarang menyetujui transaksi (403).
     */
    public function test_staff_cannot_confirm_or_reject_transaction(): void
    {
        $trx = StockTransaction::create([
            'invoice_number' => 'TRX-TEST-FORBIDDEN-'.uniqid().'-'.mt_rand(100, 999),
            'product_id' => $this->testProduct->id,
            'user_id' => $this->staffUser->id,
            'type' => 'in',
            'quantity' => 5,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->staffUser)->post("/transactions/{$trx->id}/confirm");
        $response->assertStatus(403);
    }
}
