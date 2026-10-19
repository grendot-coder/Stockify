<?php

namespace App\Services\Product;

use App\Models\ActivityLog;
use App\Repositories\Product\ProductRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use LaravelEasyRepository\Service;

class ProductServiceImplement extends Service implements ProductService
{
    /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
    protected $mainRepository;

    public function __construct(ProductRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
    }

    public function getPaginated(?string $search = null, ?int $categoryId = null, ?int $supplierId = null, bool $lowStockOnly = false, int $perPage = 10)
    {
        return $this->mainRepository->getPaginated($search, $categoryId, $supplierId, $lowStockOnly, $perPage);
    }

    public function generateSku(?int $categoryId = null): string
    {
        return $this->mainRepository->generateSku($categoryId);
    }

    public function createProduct(array $data, ?object $imageFile = null, array $attributes = [])
    {
        if ($imageFile) {
            $path = $imageFile->store('products', 'public');
            $data['image'] = $path;
        }

        if (empty($data['sku'])) {
            $data['sku'] = $this->mainRepository->generateSku($data['category_id'] ?? null);
        }

        $product = $this->mainRepository->createProduct($data, $attributes);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'CREATE_PRODUCT',
            'description' => "Menambahkan produk baru: '{$product->name}' (SKU: {$product->sku}, Stok Awal: {$product->stock}).",
            'ip_address' => request()->ip(),
        ]);

        return $product;
    }

    public function updateProduct($id, array $data, ?object $imageFile = null, array $attributes = [])
    {
        $product = $this->mainRepository->find($id);

        if ($imageFile) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $imageFile->store('products', 'public');
        }

        $updatedProduct = $this->mainRepository->updateProduct($id, $data, $attributes);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE_PRODUCT',
            'description' => "Memperbarui data produk: '{$updatedProduct->name}' (SKU: {$updatedProduct->sku}).",
            'ip_address' => request()->ip(),
        ]);

        return $updatedProduct;
    }

    public function deleteProduct($id)
    {
        $product = $this->mainRepository->find($id);
        $name = $product ? $product->name : 'Produk';
        $sku = $product ? $product->sku : '';

        if ($product && $product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $deleted = $this->mainRepository->deleteProduct($id);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'DELETE_PRODUCT',
            'description' => "Menghapus produk: '{$name}' (SKU: {$sku}).",
            'ip_address' => request()->ip(),
        ]);

        return $deleted;
    }
}
