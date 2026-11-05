<?php

namespace App\Repositories\Product;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use LaravelEasyRepository\Implementations\Eloquent;

class ProductRepositoryImplement extends Eloquent implements ProductRepository
{
    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     *
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    public function getPaginated(?string $search = null, ?int $categoryId = null, ?int $supplierId = null, bool $lowStockOnly = false, int $perPage = 10)
    {
        return $this->model->newQuery()
            ->with(['category', 'supplier', 'attributes', 'rackLocation'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when($categoryId, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($supplierId, function ($query, $supplierId) {
                $query->where('supplier_id', $supplierId);
            })
            ->when($lowStockOnly, function ($query) {
                $query->whereColumn('stock', '<=', 'min_stock');
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function generateSku(?int $categoryId = null): string
    {
        $prefix = 'PRD';
        if ($categoryId) {
            $category = Category::find($categoryId);
            if ($category) {
                $cleanCat = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $category->name), 0, 3));
                if (! empty($cleanCat)) {
                    $prefix .= '-'.$cleanCat;
                }
            }
        }

        // Ambil semua SKU yang memiliki prefix yang sama untuk mencari angka tertinggi
        $existingSkus = $this->model->where('sku', 'like', "{$prefix}-%")->pluck('sku');

        $maxNumber = 0;
        foreach ($existingSkus as $sku) {
            if (preg_match('/-(\d+)$/', $sku, $matches)) {
                $num = intval($matches[1]);
                if ($num > $maxNumber) {
                    $maxNumber = $num;
                }
            }
        }

        $nextNumber = $maxNumber + 1;

        // Loop verifikasi anti-duplikasi (collision-proof)
        do {
            $candidateSku = sprintf('%s-%03d', $prefix, $nextNumber);
            $exists = $this->model->where('sku', $candidateSku)->exists();
            if ($exists) {
                $nextNumber++;
            }
        } while ($exists);

        return $candidateSku;
    }

    public function createProduct(array $data, array $attributes = [])
    {
        return DB::transaction(function () use ($data, $attributes) {
            $product = $this->model->create($data);

            if (! empty($attributes)) {
                foreach ($attributes as $attr) {
                    if (! empty($attr['name']) && ! empty($attr['value'])) {
                        $product->attributes()->create([
                            'attribute_name' => trim($attr['name']),
                            'attribute_value' => trim($attr['value']),
                        ]);
                    }
                }
            }

            return $product;
        });
    }

    public function updateProduct($id, array $data, array $attributes = [])
    {
        return DB::transaction(function () use ($id, $data, $attributes) {
            $product = $this->model->findOrFail($id);
            $product->update($data);

            // Sync attributes: delete old ones and recreate
            $product->attributes()->delete();
            if (! empty($attributes)) {
                foreach ($attributes as $attr) {
                    if (! empty($attr['name']) && ! empty($attr['value'])) {
                        $product->attributes()->create([
                            'attribute_name' => trim($attr['name']),
                            'attribute_value' => trim($attr['value']),
                        ]);
                    }
                }
            }

            return $product;
        });
    }

    public function deleteProduct($id)
    {
        $product = $this->model->findOrFail($id);

        return $product->delete();
    }
}
