<?php

namespace App\Repositories\Product;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductRepositoryImplement extends Eloquent implements ProductRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
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
            ->with(['category', 'supplier', 'attributes'])
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
                $prefix .= '-' . strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $category->name), 0, 3));
            }
        }

        $latestProduct = $this->model->where('sku', 'like', "{$prefix}-%")->latest('id')->first();
        $nextNumber = 1;
        if ($latestProduct && preg_match('/-(\d+)$/', $latestProduct->sku, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        }

        return sprintf('%s-%03d', $prefix, $nextNumber);
    }

    public function createProduct(array $data, array $attributes = [])
    {
        return DB::transaction(function () use ($data, $attributes) {
            $product = $this->model->create($data);

            if (!empty($attributes)) {
                foreach ($attributes as $attr) {
                    if (!empty($attr['name']) && !empty($attr['value'])) {
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
            if (!empty($attributes)) {
                foreach ($attributes as $attr) {
                    if (!empty($attr['name']) && !empty($attr['value'])) {
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
