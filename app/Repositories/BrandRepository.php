<?php

namespace App\Repositories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Collection;

class BrandRepository extends BaseRepository implements BrandRepositoryInterface
{
    public function __construct(Brand $model)
    {
        parent::__construct($model);
    }

    public function getActiveBrands(): Collection
    {
        return $this->model->where('is_active', true)->orderBy('sort_order')->get();
    }
}
