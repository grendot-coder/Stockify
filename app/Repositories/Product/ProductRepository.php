<?php

namespace App\Repositories\Product;

use LaravelEasyRepository\Repository;

interface ProductRepository extends Repository
{
    public function getPaginated(?string $search = null, ?int $categoryId = null, ?int $supplierId = null, bool $lowStockOnly = false, int $perPage = 10);

    public function createProduct(array $data, array $attributes = []);

    public function updateProduct($id, array $data, array $attributes = []);

    public function deleteProduct($id);

    public function generateSku(?int $categoryId = null): string;
}
