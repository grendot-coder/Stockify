<?php

namespace App\Repositories\StockTransaction;

use LaravelEasyRepository\Repository;

interface StockTransactionRepository extends Repository{

    public function getPaginated(?string $search = null, ?string $type = null, ?string $status = null, ?int $productId = null, int $perPage = 10);

    public function generateInvoiceNumber(string $type = 'in'): string;

    public function createTransaction(array $data);

    public function updateStatus(int $id, string $status, ?int $confirmedBy = null);
}
