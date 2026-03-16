<?php

namespace App\Services;

use App\Repositories\ProductRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts($filters = [])
    {
        // Cache products for 60 minutes
        return Cache::remember('products_all', 3600, function () use ($filters) {
            return $this->productRepository->getActiveProducts($filters);
        });
    }

    public function getProductBySlug(string $slug)
    {
        return Cache::remember("product_{$slug}", 3600, function () use ($slug) {
            return $this->productRepository->findBySlug($slug);
        });
    }
}
