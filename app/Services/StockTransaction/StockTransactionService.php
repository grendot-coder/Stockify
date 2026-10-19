<?php

namespace App\Services\StockTransaction;

use LaravelEasyRepository\BaseService;

interface StockTransactionService extends BaseService
{
    public function getPaginated(?string $search = null, ?string $type = null, ?string $status = null, ?int $productId = null, int $perPage = 10);

    public function generateInvoiceNumber(string $type = 'in'): string;

    public function createInbound(array $data, int $userId);

    public function createOutbound(array $data, int $userId);

    public function confirmTransaction(int $id, int $confirmedBy);

    public function rejectTransaction(int $id, int $confirmedBy, ?string $reason = null);
}
