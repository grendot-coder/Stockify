<?php

namespace App\Repositories\Supplier;

use App\Models\Supplier;
use LaravelEasyRepository\Implementations\Eloquent;

class SupplierRepositoryImplement extends Eloquent implements SupplierRepository
{
    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     *
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(Supplier $model)
    {
        $this->model = $model;
    }

    public function getPaginated(?string $search = null, int $perPage = 10)
    {
        return $this->model->newQuery()
            ->withCount('products')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function createSupplier(array $data)
    {
        return $this->model->create($data);
    }

    public function updateSupplier($id, array $data)
    {
        $supplier = $this->model->findOrFail($id);
        $supplier->update($data);

        return $supplier;
    }

    public function deleteSupplier($id)
    {
        $supplier = $this->model->findOrFail($id);

        return $supplier->delete();
    }
}
