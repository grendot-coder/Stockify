<?php

namespace App\Services\Supplier;

use LaravelEasyRepository\BaseService;

interface SupplierService extends BaseService{

    public function getPaginated(?string $search = null, int $perPage = 10);
    public function createSupplier(array $data);
    public function updateSupplier($id, array $data);
    public function deleteSupplier($id);
}
