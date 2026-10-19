<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Services\Product\ProductService;
use Illuminate\Http\Request;

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
}
