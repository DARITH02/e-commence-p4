<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::latest()->paginate(10);
        
        // Calculate stats
        $totalCount    = Brand::count();
        $activeCount   = Brand::where('is_active', true)->count();
        $inactiveCount = Brand::where('is_active', false)->count();

        if (request()->wantsJson()) {
            return response()->json($brands);
        }

        return view('admin.brands.index', compact('brands', 'totalCount', 'activeCount', 'inactiveCount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:brands,slug',
            'logo' => 'nullable', // Can be a file (image) or a URL string
            'description' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        try {
            DB::beginTransaction();

            if ($request->hasFile('logo')) {
                $path = $request->file('logo')->store('brands', config('filesystems.default'));
                $validated['logo'] = $path;
            } elseif ($request->filled('logo') && filter_var($request->logo, FILTER_VALIDATE_URL)) {
                $validated['logo'] = $request->logo;
            }

            $brand = Brand::create($validated);

            DB::commit();

            return response()->json([
                'message' => 'Brand created successfully!',
                'brand' => $brand
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create brand: ' . $e->getMessage()], 500);
        }
    }

    public function show(Brand $brand)
    {
        return response()->json($brand);
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:brands,slug,' . $brand->id,
            'logo' => 'nullable', // Can be a file (image) or a URL string
            'description' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        try {
            DB::beginTransaction();

            if ($request->hasFile('logo')) {
                // Delete old local logo if it was a path
                if ($brand->logo && !filter_var($brand->logo, FILTER_VALIDATE_URL)) {
                    Storage::disk(config('filesystems.default'))->delete($brand->logo);
                }
                $path = $request->file('logo')->store('brands', config('filesystems.default'));
                $validated['logo'] = $path;
            } elseif ($request->filled('logo') && filter_var($request->logo, FILTER_VALIDATE_URL)) {
                $validated['logo'] = $request->logo;
            }

            $brand->update($validated);

            DB::commit();

            return response()->json([
                'message' => 'Brand updated successfully!',
                'brand' => $brand
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update brand: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Brand $brand)
    {
        if ($brand->logo) {
            Storage::disk(config('filesystems.default'))->delete($brand->logo);
        }
        $brand->delete();
        return response()->json(['message' => 'Brand deleted successfully!']);
    }
}
