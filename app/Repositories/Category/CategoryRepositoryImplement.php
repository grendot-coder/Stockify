<?php

namespace App\Repositories\Category;

use App\Models\Category;
use Illuminate\Support\Str;
use LaravelEasyRepository\Implementations\Eloquent;

class CategoryRepositoryImplement extends Eloquent implements CategoryRepository
{
    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     *
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    public function getPaginated(?string $search = null, int $perPage = 10)
    {
        return $this->model->newQuery()
            ->withCount('products')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function createCategory(array $data)
    {
        $data['slug'] = Str::slug($data['name']);
        // Ensure unique slug
        $count = $this->model->where('slug', 'like', "{$data['slug']}%")->count();
        if ($count > 0) {
            $data['slug'] .= '-'.($count + 1);
        }

        return $this->model->create($data);
    }

    public function updateCategory($id, array $data)
    {
        $category = $this->model->findOrFail($id);
        if (isset($data['name']) && $data['name'] !== $category->name) {
            $slug = Str::slug($data['name']);
            $count = $this->model->where('slug', 'like', "{$slug}%")->where('id', '!=', $id)->count();
            $data['slug'] = $count > 0 ? "{$slug}-".($count + 1) : $slug;
        }

        $category->update($data);

        return $category;
    }

    public function deleteCategory($id)
    {
        $category = $this->model->findOrFail($id);

        return $category->delete();
    }
}
