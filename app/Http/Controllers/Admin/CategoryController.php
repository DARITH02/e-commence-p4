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
            'image' => 'nullable|image|max:5120',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', config('filesystems.default'));
        }

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
            'image' => 'nullable|image|max:5120',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        if ($request->hasFile('image')) {
            // Delete old image
            if ($category->image) {
                Storage::disk(config('filesystems.default'))->delete($category->image);
            }
            $validated['image'] = $request->file('image')->store('categories', config('filesystems.default'));
        }

        $category->update($validated);

        return response()->json([
            'message' => 'Category updated successfully!',
            'category' => $category->load('parent')
        ]);
    }

    public function destroy(Category $category)
    {
        if ($category->image) {
            Storage::disk(config('filesystems.default'))->delete($category->image);
        }
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully!']);
    }
}
