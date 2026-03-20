<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\BrandService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    protected $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    public function index(Request $request)
    {
        if ($request->has('active_only')) {
            $brands = $this->brandService->getActiveBrands();
        } else {
            $brands = $this->brandService->getAllBrands();
        }
        return response()->json($brands);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:brands,slug',
            'logo' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'website' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $brand = $this->brandService->createBrand($data);
        return response()->json($brand, 201);
    }

    public function show($slug)
    {
        $brand = $this->brandService->getBrandBySlug($slug);
        if (!$brand) {
            return response()->json(['message' => 'Brand not found'], 404);
        }
        return response()->json($brand);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|max:255|unique:brands,slug,' . $id,
            'logo' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'website' => 'nullable|string|max:255',
            'is_active' => 'sometimes|boolean',
            'sort_order' => 'sometimes|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $success = $this->brandService->updateBrand($id, $request->all());
        if ($success) {
            return response()->json(['message' => 'Brand updated successfully']);
        }
        return response()->json(['message' => 'Failed to update brand'], 500);
    }

    public function destroy($id)
    {
        $success = $this->brandService->deleteBrand($id);
        if ($success) {
            return response()->json(['message' => 'Brand deleted successfully']);
        }
        return response()->json(['message' => 'Failed to delete brand'], 500);
    }
}
