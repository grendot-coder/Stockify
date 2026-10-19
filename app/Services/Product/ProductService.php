<?php

namespace App\Services\Product;

use LaravelEasyRepository\BaseService;

interface ProductService extends BaseService
{
    public function getPaginated(?string $search = null, ?int $categoryId = null, ?int $supplierId = null, bool $lowStockOnly = false, int $perPage = 10);

    public function createProduct(array $data, ?object $imageFile = null, array $attributes = []);

    public function updateProduct($id, array $data, ?object $imageFile = null, array $attributes = []);

    public function deleteProduct($id);

    public function generateSku(?int $categoryId = null): string;
}
