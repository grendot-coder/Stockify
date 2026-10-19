<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MasterDataTest extends TestCase
{
    protected $adminUser;
    protected $staffUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::where('role', 'admin')->first();
        if (!$this->adminUser) {
            $this->adminUser = User::factory()->create(['role' => 'admin', 'is_active' => true]);
        }

        $this->staffUser = User::where('role', 'staff')->first();
        if (!$this->staffUser) {
            $this->staffUser = User::factory()->create(['role' => 'staff', 'is_active' => true]);
        }
    }

    /**
     * Test admin dapat mengakses halaman kategori dan menambahkan kategori baru.
     */
    public function test_admin_can_manage_categories(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/categories');
        $response->assertStatus(200);
        $response->assertSee('Kategori Produk');

        $uniqueName = 'Kategori Uji ' . uniqid();
        $createResponse = $this->actingAs($this->adminUser)->post('/categories', [
            'name' => $uniqueName,
            'description' => 'Deskripsi uji coba kategori',
        ]);

        $createResponse->assertRedirect('/categories');
        $this->assertDatabaseHas('categories', ['name' => $uniqueName]);
    }

    /**
     * Test admin dapat mengakses halaman supplier dan menambahkan supplier baru.
     */
    public function test_admin_can_manage_suppliers(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/suppliers');
        $response->assertStatus(200);
        $response->assertSee('Data Rekanan Supplier');

        $uniqueName = 'PT Supplier Test ' . uniqid();
        $createResponse = $this->actingAs($this->adminUser)->post('/suppliers', [
            'name' => $uniqueName,
            'phone' => '081298765432',
            'email' => 'supplier@test.com',
            'address' => 'Jl. Industri No. 45 Jakarta',
        ]);

        $createResponse->assertRedirect('/suppliers');
        $this->assertDatabaseHas('suppliers', ['name' => $uniqueName]);
    }

    /**
     * Test endpoint generate SKU internal berfungsi.
     */
    public function test_generate_sku_api(): void
    {
        $category = Category::first();
        $response = $this->actingAs($this->adminUser)->getJson('/api/products/generate-sku?category_id=' . ($category ? $category->id : ''));
        $response->assertStatus(200);
        $response->assertJsonStructure(['sku']);
    }

    /**
     * Test admin dapat membuat produk baru lengkap dengan atribut.
     */
    public function test_admin_can_create_and_view_product(): void
    {
        $category = Category::first() ?? Category::create(['name' => 'Kategori Dummy']);
        $supplier = Supplier::first() ?? Supplier::create(['name' => 'Supplier Dummy', 'phone' => '0812345']);

        $sku = 'TEST-' . rand(1000, 9999);
        $createResponse = $this->actingAs($this->adminUser)->post('/products', [
            'category_id' => $category->id,
            'supplier_id' => $supplier->id,
            'sku' => $sku,
            'name' => 'Laptop ThinkPad T480s',
            'description' => 'Laptop bisnis tangguh',
            'purchase_price' => 5000000,
            'selling_price' => 6500000,
            'stock' => 10,
            'min_stock' => 2,
            'attributes' => [
                ['name' => 'RAM', 'value' => '16 GB'],
                ['name' => 'Storage', 'value' => '512 GB SSD'],
            ],
        ]);

        $createResponse->assertRedirect('/products');
        $this->assertDatabaseHas('products', ['sku' => $sku]);
        $this->assertDatabaseHas('product_attributes', ['attribute_name' => 'RAM', 'attribute_value' => '16 GB']);

        $product = Product::where('sku', $sku)->first();
        $showResponse = $this->actingAs($this->adminUser)->get("/products/{$product->id}");
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Laptop ThinkPad T480s');
        $showResponse->assertSee('16 GB');
    }

    /**
     * Test staff hanya bisa melihat produk dan dilarang mengubah master data kategori.
     */
    public function test_staff_role_permissions(): void
    {
        // Staff bisa melihat daftar produk
        $productIndexResponse = $this->actingAs($this->staffUser)->get('/products');
        $productIndexResponse->assertStatus(200);

        // Staff dilarang membuka form tambah produk
        $productCreateResponse = $this->actingAs($this->staffUser)->get('/products/create');
        $productCreateResponse->assertStatus(403);

        // Staff dilarang membuka kelola kategori
        $categoryResponse = $this->actingAs($this->staffUser)->get('/categories');
        $categoryResponse->assertStatus(403);
    }
}
