<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['categories', 'images', 'brand'])->latest()->paginate(10);
        $categories = Category::all();
        $brands = \App\Models\Brand::all();

        // Calculate global counts
        $totalCount    = Product::count();
        $activeCount   = Product::where('is_active', true)->count();
        $lowStockCount = Product::where('stock_status', 'outofstock')->count(); // Using stock_status as fallback
        $inactiveCount = Product::where('is_active', false)->count();

        if (request()->wantsJson()) {
            return response()->json($products);
        }

        return view('admin.products.index', compact('products', 'categories', 'brands', 'totalCount', 'activeCount', 'lowStockCount', 'inactiveCount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|unique:products,sku',
            'categories' => 'required|array',
            'brand_id' => 'nullable|exists:brands,id',
            'is_active' => 'boolean',
            'images' => 'nullable|array',
            'images.*' => 'nullable', // Support file or URL
            'image_urls' => 'nullable|array',
            'image_urls.*' => 'nullable|url',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        try {
            DB::beginTransaction();

            $product = Product::create($validated);
            $product->categories()->attach($request->categories);

            // Handle Images
            // Handle Uploaded Images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', config('filesystems.default'));
                    $product->images()->create([
                        'image_path' => $path,
                        'is_primary' => !($product->images()->count() > 0) && $index === 0
                    ]);
                }
            }

            // Handle Image URLs
            if ($request->filled('image_urls')) {
                foreach ($request->image_urls as $url) {
                    if (!empty($url)) {
                        $product->images()->create([
                            'image_path' => $url,
                            'is_primary' => !($product->images()->count() > 0)
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Product created successfully!',
                'product' => $product->load(['categories', 'images', 'brand'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create product: ' . $e->getMessage()], 500);
        }
    }

    public function show(Product $product)
    {
        return response()->json($product->load(['categories', 'images', 'brand', 'variants.values']));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'categories' => 'required|array',
            'brand_id' => 'nullable|exists:brands,id',
            'is_active' => 'boolean',
            'images.*' => 'nullable',
            'image_urls' => 'nullable|array',
            'image_urls.*' => 'nullable|url',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        try {
            DB::beginTransaction();

            $product->update($validated);
            $product->categories()->sync($request->categories);

            // Handle New Images
            // Handle Uploaded Images
            if ($request->hasFile('images')) {
                $hasPrimary = $product->images()->where('is_primary', true)->exists();
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', config('filesystems.default'));
                    $product->images()->create([
                        'image_path' => $path,
                        'is_primary' => !$hasPrimary && $index === 0
                    ]);
                    $hasPrimary = true;
                }
            }

            // Handle Image URLs
            if ($request->filled('image_urls')) {
                $hasPrimary = $product->images()->where('is_primary', true)->exists();
                foreach ($request->image_urls as $url) {
                    if (!empty($url)) {
                         $product->images()->create([
                            'image_path' => $url,
                            'is_primary' => !$hasPrimary
                        ]);
                        $hasPrimary = true;
                    }
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Product updated successfully!',
                'product' => $product->load(['categories', 'images', 'brand'])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update product: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully!']);
    }

    public function destroyImage(\App\Models\ProductImage $image)
    {
        Storage::disk(config('filesystems.default'))->delete($image->image_path);
        $image->delete();
        return response()->json(['message' => 'Image deleted successfully!']);
    }
}
