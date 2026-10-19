<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProductImportExportTest extends TestCase
{
    protected $adminUser;

    protected $staffUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::where('role', 'admin')->first()
            ?? User::factory()->create(['role' => 'admin', 'is_active' => true]);

        $this->staffUser = User::where('role', 'staff')->first()
            ?? User::factory()->create(['role' => 'staff', 'is_active' => true]);
    }

    /**
     * Admin dapat mengekspor katalog produk ke berkas CSV.
     */
    public function test_admin_can_export_products_csv(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/products/export');
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('Content-Disposition') ?? '', 'katalog-produk-stockify-'));
    }

    /**
     * Admin dapat mengunduh template CSV untuk impor.
     */
    public function test_admin_can_download_import_template(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/products/import/template');
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('Content-Disposition') ?? '', 'template-import-produk-stockify.csv'));
    }

    /**
     * Admin dapat mengimpor produk baru melalui berkas CSV.
     */
    public function test_admin_can_import_products_csv(): void
    {
        $sku = 'IMP-'.uniqid().'-'.mt_rand(100, 999);
        $csvContent = "sku,name,category,supplier,purchase_price,selling_price,stock,min_stock,description\n";
        $csvContent .= "{$sku},Kamera DSLR Pro,Fotografi,Vendor Kamera Jaya,5000000,7500000,12,3,Kamera profesional lensa kit\n";

        $file = UploadedFile::fake()->createWithContent('import-test.csv', $csvContent);

        $response = $this->actingAs($this->adminUser)->post('/products/import', [
            'file' => $file,
        ]);

        $response->assertRedirect('/products');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'sku' => $sku,
            'name' => 'Kamera DSLR Pro',
            'stock' => 12,
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Fotografi',
        ]);
    }

    /**
     * Staff dilarang mengimpor produk (403).
     */
    public function test_staff_cannot_import_products(): void
    {
        $file = UploadedFile::fake()->createWithContent('import-test.csv', "sku,name\n");

        $response = $this->actingAs($this->staffUser)->post('/products/import', [
            'file' => $file,
        ]);

        $response->assertStatus(403);
    }
}
