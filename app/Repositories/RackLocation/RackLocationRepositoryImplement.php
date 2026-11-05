<?php

namespace App\Repositories\RackLocation;

use App\Models\RackLocation;
use LaravelEasyRepository\Implementations\Eloquent;

class RackLocationRepositoryImplement extends Eloquent implements RackLocationRepository
{
    /**
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(RackLocation $model)
    {
        $this->model = $model;
    }

    public function getAllWithStock()
    {
        return $this->model->newQuery()
            ->withSum('products', 'stock')
            ->orderBy('name', 'asc')
            ->get();
    }

    public function getPaginated(?string $search = null, int $perPage = 10)
    {
        return $this->model->newQuery()
            ->withSum('products', 'stock')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('zone', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function createRackLocation(array $data)
    {
        return $this->model->create($data);
    }

    public function updateRackLocation($id, array $data)
    {
        $rackLocation = $this->model->findOrFail($id);
        $rackLocation->update($data);

        return $rackLocation;
    }

    public function deleteRackLocation($id)
    {
        $rackLocation = $this->model->findOrFail($id);

        return $rackLocation->delete();
    }
}
