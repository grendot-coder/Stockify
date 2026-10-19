<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Services\Product\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Tampilkan daftar produk dengan filter dan paginasi.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');
        $supplierId = $request->query('supplier_id');
        $lowStock = $request->boolean('low_stock');

        $products = $this->productService->getPaginated($search, $categoryId ? intval($categoryId) : null, $supplierId ? intval($supplierId) : null, $lowStock, 10);
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('pages.products.index', compact('products', 'categories', 'suppliers', 'search', 'categoryId', 'supplierId', 'lowStock'));
    }

    /**
     * Formulir tambah produk baru.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();
        $suggestedSku = $this->productService->generateSku();

        return view('pages.products.create', compact('categories', 'suppliers', 'suggestedSku'));
    }

    /**
     * Simpan data produk baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'sku' => ['required', 'string', 'max:50', 'unique:products,sku'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'attributes' => ['nullable', 'array'],
            'attributes.*.name' => ['nullable', 'string', 'max:100'],
            'attributes.*.value' => ['nullable', 'string', 'max:255'],
        ], [
            'category_id.required' => 'Pilih kategori produk.',
            'sku.required' => 'SKU/Kode barang wajib diisi.',
            'sku.unique' => 'SKU ini sudah digunakan produk lain.',
            'name.required' => 'Nama produk wajib diisi.',
            'purchase_price.required' => 'Harga beli wajib diisi.',
            'selling_price.required' => 'Harga jual wajib diisi.',
            'stock.required' => 'Jumlah stok awal wajib diisi.',
            'image.max' => 'Ukuran foto maksimal adalah 2MB.',
        ]);

        $attributes = $request->input('attributes', []);
        $imageFile = $request->file('image');

        $this->productService->createProduct($validated, $imageFile, $attributes);

        return redirect()->route('products.index')->with('success', 'Produk baru berhasil disimpan ke sistem.');
    }

    /**
     * Detail produk.
     */
    public function show($id)
    {
        $product = Product::with(['category', 'supplier', 'attributes', 'stockTransactions' => function ($q) {
            $q->latest()->take(10);
        }])->findOrFail($id);

        return view('pages.products.show', compact('product'));
    }

    /**
     * Formulir ubah produk.
     */
    public function edit($id)
    {
        $product = Product::with('attributes')->findOrFail($id);
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::orderBy('name')->get();

        return view('pages.products.edit', compact('product', 'categories', 'suppliers'));
    }

    /**
     * Perbarui data produk.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'sku' => ['required', 'string', 'max:50', "unique:products,sku,{$id}"],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'attributes' => ['nullable', 'array'],
            'attributes.*.name' => ['nullable', 'string', 'max:100'],
            'attributes.*.value' => ['nullable', 'string', 'max:255'],
        ]);

        $attributes = $request->input('attributes', []);
        $imageFile = $request->file('image');

        $this->productService->updateProduct($id, $validated, $imageFile, $attributes);

        return redirect()->route('products.index')->with('success', 'Data produk berhasil diperbarui.');
    }

    /**
     * Hapus produk.
     */
    public function destroy($id)
    {
        try {
            $this->productService->deleteProduct($id);

            return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('products.index')->with('error', 'Gagal menghapus produk: Masih terdapat riwayat transaksi yang terkait.');
        }
    }

    /**
     * Endpoint API internal untuk generate SKU dinamis via AJAX saat ganti kategori.
     */
    public function generateSku(Request $request)
    {
        $categoryId = $request->query('category_id');
        $sku = $this->productService->generateSku($categoryId ? intval($categoryId) : null);

        return response()->json(['sku' => $sku]);
    }

    /**
     * Ekspor seluruh katalog produk ke berkas CSV.
     */
    public function export()
    {
        $products = Product::with(['category', 'supplier'])->orderBy('name')->get();
        $filename = 'katalog-produk-stockify-'.date('Ymd-His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($products) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'SKU',
                'Nama Produk',
                'Kategori',
                'Supplier Rekanan',
                'Harga Beli',
                'Harga Jual',
                'Stok Fisik',
                'Batas Min Stok',
                'Deskripsi',
                'Tanggal Ditambahkan',
            ]);

            foreach ($products as $p) {
                fputcsv($handle, [
                    $p->sku,
                    $p->name,
                    $p->category?->name ?? 'Umum',
                    $p->supplier?->name ?? '-',
                    $p->purchase_price,
                    $p->selling_price,
                    $p->stock,
                    $p->min_stock,
                    $p->description ?? '',
                    $p->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Unduh template CSV untuk import produk.
     */
    public function sampleTemplate()
    {
        $filename = 'template-import-produk-stockify.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'sku',
                'name',
                'category',
                'supplier',
                'purchase_price',
                'selling_price',
                'stock',
                'min_stock',
                'description',
            ]);

            fputcsv($handle, [
                'CONTOH-SKU-001',
                'Mouse Wireless Ergonomic',
                'Elektronik',
                'PT Sumber Makmur',
                '75000',
                '120000',
                '50',
                '10',
                'Mouse nirkabel 2.4Ghz dengan sensor presisi tinggi',
            ]);

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Impor katalog produk dari berkas CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ], [
            'file.required' => 'Pilih berkas CSV produk untuk diimpor.',
            'file.mimes' => 'Format berkas harus berupa CSV (.csv).',
            'file.max' => 'Ukuran berkas maksimal 5 MB.',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');

        $bom = fread($handle, 3);
        if ($bom !== "\xEF\xBB\xBF") {
            rewind($handle);
        }

        $header = fgetcsv($handle, 1000, ',');
        if (! $header) {
            fclose($handle);

            return redirect()->back()->with('error', 'Berkas CSV kosong atau tidak memiliki baris header.');
        }

        $header = array_map(function ($col) {
            return strtolower(trim($col));
        }, $header);

        $imported = 0;
        $updated = 0;

        while (($row = fgetcsv($handle, 2000, ',')) !== false) {
            if (count($row) < 2 || empty($row[0])) {
                continue;
            }

            $data = array_combine(array_slice($header, 0, count($row)), $row);

            $sku = trim($data['sku'] ?? '');
            $name = trim($data['name'] ?? '');
            if (empty($sku) || empty($name)) {
                continue;
            }

            $categoryName = trim($data['category'] ?? 'Umum');
            $category = Category::where('name', $categoryName)->first();
            if (! $category) {
                $slug = Str::slug($categoryName) ?: 'kategori-'.uniqid();
                $count = Category::where('slug', 'like', "{$slug}%")->count();
                if ($count > 0) {
                    $slug .= '-'.($count + 1);
                }
                $category = Category::create([
                    'name' => $categoryName,
                    'slug' => $slug,
                ]);
            }

            $supplierId = null;
            if (! empty($data['supplier'])) {
                $supplierName = trim($data['supplier']);
                $supplier = Supplier::where('name', $supplierName)->first();
                if (! $supplier) {
                    $supplier = Supplier::create([
                        'name' => $supplierName,
                        'phone' => '-',
                    ]);
                }
                $supplierId = $supplier->id;
            }

            $purchasePrice = isset($data['purchase_price']) ? floatval(str_replace(',', '', $data['purchase_price'])) : 0;
            $sellingPrice = isset($data['selling_price']) ? floatval(str_replace(',', '', $data['selling_price'])) : 0;
            $stock = isset($data['stock']) ? intval($data['stock']) : 0;
            $minStock = isset($data['min_stock']) ? intval($data['min_stock']) : 5;
            $description = trim($data['description'] ?? '');

            $existing = Product::where('sku', $sku)->first();

            if ($existing) {
                $existing->update([
                    'category_id' => $category->id,
                    'supplier_id' => $supplierId ?? $existing->supplier_id,
                    'name' => $name,
                    'purchase_price' => $purchasePrice,
                    'selling_price' => $sellingPrice,
                    'stock' => $stock,
                    'min_stock' => $minStock,
                    'description' => $description,
                ]);
                $updated++;
            } else {
                Product::create([
                    'category_id' => $category->id,
                    'supplier_id' => $supplierId,
                    'sku' => $sku,
                    'name' => $name,
                    'purchase_price' => $purchasePrice,
                    'selling_price' => $sellingPrice,
                    'stock' => $stock,
                    'min_stock' => $minStock,
                    'description' => $description,
                ]);
                $imported++;
            }
        }

        fclose($handle);

        return redirect()->route('products.index')->with('success', "Proses impor selesai! {$imported} produk baru ditambahkan dan {$updated} produk diperbarui.");
    }
}
