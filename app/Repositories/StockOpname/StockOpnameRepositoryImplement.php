<?php

namespace App\Repositories\StockOpname;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\StockOpname;

class StockOpnameRepositoryImplement extends Eloquent implements StockOpnameRepository
{
    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property StockOpname|mixed $model;
     */
    protected $model;

    public function __construct(StockOpname $model)
    {
        $this->model = $model;
    }

    public function getPaginated(?string $search = null, ?string $dateFrom = null, ?string $dateTo = null, ?int $productId = null, int $perPage = 10)
    {
        return $this->model->newQuery()
            ->with(['product.category', 'user'])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('notes', 'like', "%{$search}%")
                        ->orWhereHas('product', function ($pq) use ($search) {
                            $pq->where('name', 'like', "%{$search}%")
                                ->orWhere('sku', 'like', "%{$search}%");
                        })
                        ->orWhereHas('user', function ($uq) use ($search) {
                            $uq->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($dateFrom, function ($query, $dateFrom) {
                $query->whereDate('opname_date', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query, $dateTo) {
                $query->whereDate('opname_date', '<=', $dateTo);
            })
            ->when($productId, function ($query, $productId) {
                $query->where('product_id', $productId);
            })
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getStats(): array
    {
        $total = $this->model->count();
        $matched = $this->model->where('difference', 0)->count();
        $deficit = $this->model->where('difference', '<', 0)->count();
        $surplus = $this->model->where('difference', '>', 0)->count();

        return [
            'total' => $total,
            'matched' => $matched,
            'deficit' => $deficit,
            'surplus' => $surplus,
        ];
    }

    public function createOpname(array $data)
    {
        return $this->model->create($data);
    }
}
