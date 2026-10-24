<?php

namespace App\Repositories\RackLocation;

use LaravelEasyRepository\Repository;

interface RackLocationRepository extends Repository
{
    public function getAllWithStock();

    public function getPaginated(?string $search = null, int $perPage = 10);

    public function createRackLocation(array $data);

    public function updateRackLocation($id, array $data);

    public function deleteRackLocation($id);
}
