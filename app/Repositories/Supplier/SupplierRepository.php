<?php

namespace App\Repositories\Supplier;

use LaravelEasyRepository\Repository;

interface SupplierRepository extends Repository{

    public function getPaginated(?string $search = null, int $perPage = 10);
    public function createSupplier(array $data);
    public function updateSupplier($id, array $data);
    public function deleteSupplier($id);
}
