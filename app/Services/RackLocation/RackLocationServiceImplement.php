<?php

namespace App\Services\RackLocation;

use App\Models\ActivityLog;
use App\Repositories\RackLocation\RackLocationRepository;
use Illuminate\Support\Facades\Auth;
use LaravelEasyRepository\Service;

class RackLocationServiceImplement extends Service implements RackLocationService
{
    protected $mainRepository;

    public function __construct(RackLocationRepository $mainRepository)
    {
        $this->mainRepository = $mainRepository;
    }

    public function getAllWithStock()
    {
        return $this->mainRepository->getAllWithStock();
    }

    /**
     * Hitung statistik agregat kapasitas untuk visualizer dot matrix.
     */
    public function getMatrixStats()
    {
        $racks = $this->getAllWithStock();

        $totalCapacity = $racks->sum('capacity');
        $totalStock = $racks->sum('current_stock');
        $overallPercentage = $totalCapacity > 0 ? (int) min(100, round(($totalStock / $totalCapacity) * 100)) : 0;

        $emptyCount = 0;
        $normalCount = 0;
        $almostFullCount = 0;
        $fullCount = 0;

        foreach ($racks as $rack) {
            match ($rack->status) {
                'empty' => $emptyCount++,
                'normal' => $normalCount++,
                'almost_full' => $almostFullCount++,
                'full' => $fullCount++,
            };
        }

        return [
            'total_racks' => $racks->count(),
            'total_capacity' => $totalCapacity,
            'total_stock' => $totalStock,
            'overall_percentage' => $overallPercentage,
            'empty_count' => $emptyCount,
            'normal_count' => $normalCount,
            'almost_full_count' => $almostFullCount,
            'full_count' => $fullCount,
        ];
    }

    public function getPaginated(?string $search = null, int $perPage = 10)
    {
        return $this->mainRepository->getPaginated($search, $perPage);
    }

    public function createRackLocation(array $data)
    {
        $location = $this->mainRepository->createRackLocation($data);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'CREATE_RACK_LOCATION',
            'description' => "Menambahkan lokasi rak baru: '{$location->name}'.",
            'ip_address' => request()->ip(),
        ]);

        return $location;
    }

    public function updateRackLocation($id, array $data)
    {
        $location = $this->mainRepository->updateRackLocation($id, $data);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE_RACK_LOCATION',
            'description' => "Memperbarui lokasi rak: '{$location->name}'.",
            'ip_address' => request()->ip(),
        ]);

        return $location;
    }

    public function deleteRackLocation($id)
    {
        $location = $this->mainRepository->find($id);
        $name = $location ? $location->name : 'Lokasi Rak';

        $deleted = $this->mainRepository->deleteRackLocation($id);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'DELETE_RACK_LOCATION',
            'description' => "Menghapus lokasi rak: '{$name}'.",
            'ip_address' => request()->ip(),
        ]);

        return $deleted;
    }
}
