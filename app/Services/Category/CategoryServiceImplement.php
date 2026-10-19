<?php

namespace App\Services\Category;

use LaravelEasyRepository\Service;
use App\Repositories\Category\CategoryRepository;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class CategoryServiceImplement extends Service implements CategoryService{

    /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
    protected $mainRepository;

    public function __construct(CategoryRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    public function getPaginated(?string $search = null, int $perPage = 10)
    {
        return $this->mainRepository->getPaginated($search, $perPage);
    }

    public function createCategory(array $data)
    {
        $category = $this->mainRepository->createCategory($data);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'CREATE_CATEGORY',
            'description' => "Menambahkan kategori produk baru: '{$category->name}'.",
            'ip_address' => request()->ip(),
        ]);

        return $category;
    }

    public function updateCategory($id, array $data)
    {
        $category = $this->mainRepository->updateCategory($id, $data);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'UPDATE_CATEGORY',
            'description' => "Memperbarui kategori produk: '{$category->name}'.",
            'ip_address' => request()->ip(),
        ]);

        return $category;
    }

    public function deleteCategory($id)
    {
        $category = $this->mainRepository->find($id);
        $name = $category ? $category->name : 'Kategori';

        $deleted = $this->mainRepository->deleteCategory($id);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'DELETE_CATEGORY',
            'description' => "Menghapus kategori produk: '{$name}'.",
            'ip_address' => request()->ip(),
        ]);

        return $deleted;
    }
}
