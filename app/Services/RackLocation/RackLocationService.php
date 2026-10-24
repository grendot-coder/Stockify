<?php

namespace App\Services\RackLocation;

use LaravelEasyRepository\BaseService;

interface RackLocationService extends BaseService
{
    public function getAllWithStock();

    public function getMatrixStats();

    public function getPaginated(?string $search = null, int $perPage = 10);

    public function createRackLocation(array $data);

    public function updateRackLocation($id, array $data);

    public function deleteRackLocation($id);
}
