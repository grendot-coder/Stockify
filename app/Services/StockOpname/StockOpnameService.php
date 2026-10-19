<?php

namespace App\Services\StockOpname;

use LaravelEasyRepository\BaseService;

interface StockOpnameService extends BaseService
{
    public function getPaginated(?string $search = null, ?string $dateFrom = null, ?string $dateTo = null, ?int $productId = null, int $perPage = 10);

    public function getStats(): array;

    public function createOpname(array $data, int $userId, bool $adjustStock = false);
}
