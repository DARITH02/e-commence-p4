<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('is_active', true)
            ->with(['children' => function($q) {
                $q->where('is_active', true);
            }])
            ->whereNull('parent_id')
            ->get();

        return response()->json($categories);
    }

    public function show($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->with(['children' => function($q) {
                $q->where('is_active', true);
            }, 'products' => function($q) {
                $q->where('is_active', true)->with('images');
            }])
            ->first();

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        return response()->json($category);
    }
}
