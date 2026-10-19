<?php

namespace App\Repositories\Category;

use LaravelEasyRepository\Repository;

interface CategoryRepository extends Repository
{
    public function getPaginated(?string $search = null, int $perPage = 10);

    public function createCategory(array $data);

    public function updateCategory($id, array $data);

    public function deleteCategory($id);
}
