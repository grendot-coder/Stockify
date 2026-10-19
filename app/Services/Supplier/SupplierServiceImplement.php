<?php

namespace App\Services\Supplier;

use LaravelEasyRepository\Service;
use App\Repositories\Supplier\SupplierRepository;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class SupplierServiceImplement extends Service implements SupplierService{

    /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
    protected $mainRepository;

    public function __construct(SupplierRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    public function getPaginated(?string $search = null, int $perPage = 10)
    {
        return $this->mainRepository->getPaginated($search, $perPage);
    }

    public function createSupplier(array $data)
    {
        $supplier = $this->mainRepository->createSupplier($data);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'CREATE_SUPPLIER',
            'description' => "Menambahkan data supplier baru: '{$supplier->name}'.",
            'ip_address' => request()->ip(),
        ]);

        return $supplier;
    }

    public function updateSupplier($id, array $data)
    {
        $supplier = $this->mainRepository->updateSupplier($id, $data);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE_SUPPLIER',
            'description' => "Memperbarui data supplier: '{$supplier->name}'.",
            'ip_address' => request()->ip(),
        ]);

        return $supplier;
    }

    public function deleteSupplier($id)
    {
        $supplier = $this->mainRepository->find($id);
        $name = $supplier ? $supplier->name : 'Supplier';

        $deleted = $this->mainRepository->deleteSupplier($id);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'DELETE_SUPPLIER',
            'description' => "Menghapus data supplier: '{$name}'.",
            'ip_address' => request()->ip(),
        ]);

        return $deleted;
    }
}
