<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $products = $this->productService->getAllProducts($request->all());
        return response()->json($products);
    }

    public function show($slug)
    {
        $product = $this->productService->getProductBySlug($slug);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($product);
    }

    public function featured()
    {
        return response()->json(
            \App\Models\Product::active()->featured()->with('images')->take(8)->get()
        );
    }

    public function latest()
    {
        return response()->json(
            \App\Models\Product::active()->latest()->with('images')->take(8)->get()
        );
    }
}
