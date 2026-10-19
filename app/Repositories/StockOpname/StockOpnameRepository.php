<?php

namespace App\Repositories\StockOpname;

use LaravelEasyRepository\Repository;

interface StockOpnameRepository extends Repository
{
    public function getPaginated(?string $search = null, ?string $dateFrom = null, ?string $dateTo = null, ?int $productId = null, int $perPage = 10);

    public function getStats(): array;

    public function createOpname(array $data);
}
