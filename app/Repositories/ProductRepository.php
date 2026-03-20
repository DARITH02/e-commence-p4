<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    public function getActiveProducts(array $filters): Collection
    {
        $query = $this->model->active();

        if (isset($filters['category_id'])) {
            $query->whereHas('categories', function ($q) use ($filters) {
                $q->where('categories.id', $filters['category_id']);
            });
        }

        if (isset($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        return $query->with(['images', 'categories', 'brand'])->get();
    }

    public function getFeaturedProducts(): Collection
    {
        return $this->model->featured()->with('images')->get();
    }
    public function findBySlug(string $slug): ?\Illuminate\Database\Eloquent\Model
    {
        return $this->model->where('slug', $slug)
            ->with(['images', 'categories', 'brand', 'variants.values', 'reviews'])
            ->first();
    }
}
