<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['categories', 'images'])->latest()->paginate(10);
        $categories = Category::all();

        if (request()->wantsJson()) {
            return response()->json($products);
        }

        return view('admin.products.index', compact('products', 'categories'));
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
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        try {
            DB::beginTransaction();

            $product = Product::create($validated);
            $product->categories()->attach($request->categories);

            DB::commit();

            return response()->json([
                'message' => 'Product created successfully!',
                'product' => $product->load('categories')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create product'], 500);
        }
    }

    public function show(Product $product)
    {
        return response()->json($product->load(['categories', 'images', 'variants.values']));
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
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        try {
            DB::beginTransaction();

            $product->update($validated);
            $product->categories()->sync($request->categories);

            DB::commit();

            return response()->json([
                'message' => 'Product updated successfully!',
                'product' => $product->load('categories')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update product'], 500);
        }
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully!']);
    }
}
