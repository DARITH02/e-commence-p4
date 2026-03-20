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
        $version = \Illuminate\Support\Facades\Cache::get('product_cache_version', 1);
        $cacheKey = "products_all_v{$version}_" . md5(json_encode($filters));

        // Cache products for 60 minutes
        return Cache::remember($cacheKey, 3600, function () use ($filters) {
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
