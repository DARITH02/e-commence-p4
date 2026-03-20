<?php

namespace App\Services;

use App\Repositories\BrandRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class BrandService
{
    protected $brandRepository;

    public function __construct(BrandRepositoryInterface $brandRepository)
    {
        $this->brandRepository = $brandRepository;
    }

    public function getAllBrands()
    {
        return Cache::remember('brands_all', 3600, function () {
            return $this->brandRepository->all();
        });
    }

    public function getActiveBrands()
    {
        return Cache::remember('brands_active', 3600, function () {
            return $this->brandRepository->getActiveBrands();
        });
    }

    public function getBrandById(int $id)
    {
        return $this->brandRepository->find($id);
    }

    public function getBrandBySlug(string $slug)
    {
        return Cache::remember("brand_{$slug}", 3600, function () use ($slug) {
            return $this->brandRepository->findBySlug($slug);
        });
    }

    public function createBrand(array $data)
    {
        $brand = $this->brandRepository->create($data);
        $this->clearCache();
        return $brand;
    }

    public function updateBrand(int $id, array $data)
    {
        $result = $this->brandRepository->update($id, $data);
        $this->clearCache();
        return $result;
    }

    public function deleteBrand(int $id)
    {
        $result = $this->brandRepository->delete($id);
        $this->clearCache();
        return $result;
    }

    protected function clearCache()
    {
        Cache::forget('brands_all');
        Cache::forget('brands_active');
    }
}
