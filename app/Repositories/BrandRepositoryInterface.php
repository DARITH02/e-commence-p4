<?php

namespace App\Repositories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Collection;

interface BrandRepositoryInterface extends BaseRepositoryInterface
{
    public function getActiveBrands(): Collection;
}
