<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')->withCount('products')->latest()->paginate(15);
        $parentCategories = Category::whereNull('parent_id')->get();
        
        // Global Stats
        $totalCount    = Category::count();
        $activeCount   = Category::where('is_active', true)->count();
        $rootCount     = Category::whereNull('parent_id')->count();
        $inactiveCount = Category::where('is_active', false)->count();

        if (request()->wantsJson()) {
            return response()->json($categories);
        }

        return view('admin.categories.index', compact(
            'categories', 
            'parentCategories',
            'totalCount',
            'activeCount',
            'rootCount',
            'inactiveCount'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        // Handle image if provided (base64 or file, here assuming standard for now or AJAX base64)
        // For simplicity in this demo, we'll focus on the text data and status.

        $category = Category::create($validated);

        return response()->json([
            'message' => 'Category created successfully!',
            'category' => $category->load('parent')
        ]);
    }

    public function show(Category $category)
    {
        return response()->json($category);
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        $category->update($validated);

        return response()->json([
            'message' => 'Category updated successfully!',
            'category' => $category->load('parent')
        ]);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully!']);
    }
}
