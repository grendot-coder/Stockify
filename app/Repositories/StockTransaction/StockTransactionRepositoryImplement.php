<?php

namespace App\Repositories\StockTransaction;

use App\Models\StockTransaction;
use Carbon\Carbon;
use LaravelEasyRepository\Implementations\Eloquent;

class StockTransactionRepositoryImplement extends Eloquent implements StockTransactionRepository
{
    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     *
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(StockTransaction $model)
    {
        $this->model = $model;
    }

    public function getPaginated(?string $search = null, ?string $type = null, ?string $status = null, ?int $productId = null, int $perPage = 10)
    {
        return $this->model->newQuery()
            ->with(['product.category', 'supplier', 'user', 'confirmedByUser'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('invoice_number', 'like', "%{$search}%")
                        ->orWhere('notes', 'like', "%{$search}%")
                        ->orWhereHas('product', function ($pq) use ($search) {
                            $pq->where('name', 'like', "%{$search}%")
                                ->orWhere('sku', 'like', "%{$search}%");
                        })
                        ->orWhereHas('supplier', function ($sq) use ($search) {
                            $sq->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($type && in_array($type, ['in', 'out']), function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->when($status && in_array($status, ['pending', 'confirmed', 'rejected']), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($productId, function ($query, $productId) {
                $query->where('product_id', $productId);
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function generateInvoiceNumber(string $type = 'in'): string
    {
        $typePrefix = ($type === 'in') ? 'IN' : 'OUT';
        $dateStr = Carbon::now()->format('Ymd');
        $prefix = "TF-{$typePrefix}-{$dateStr}";

        $latestTrx = $this->model->where('invoice_number', 'like', "{$prefix}-%")->latest('id')->first();
        $nextNumber = 1;
        if ($latestTrx && preg_match('/-(\d+)$/', $latestTrx->invoice_number, $matches)) {
            $nextNumber = intval($matches[1]) + 1;
        }

        return sprintf('%s-%04d', $prefix, $nextNumber);
    }

    public function createTransaction(array $data)
    {
        return $this->model->create($data);
    }

    public function updateStatus(int $id, string $status, ?int $confirmedBy = null)
    {
        $transaction = $this->model->findOrFail($id);
        $updateData = ['status' => $status];

        if ($status === 'confirmed' || $status === 'rejected') {
            $updateData['confirmed_by'] = $confirmedBy;
            $updateData['confirmed_at'] = Carbon::now();
        }

        $transaction->update($updateData);

        return $transaction;
    }
}
