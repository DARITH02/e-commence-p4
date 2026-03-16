<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface extends BaseRepositoryInterface
{
    public function getActiveProducts(array $filters): Collection;
    public function getFeaturedProducts(): Collection;
}
