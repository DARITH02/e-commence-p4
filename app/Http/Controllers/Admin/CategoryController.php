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
        $query = Category::with('parent')->withCount('products');
        
        if (auth()->check() && auth()->user()->isSuperAdmin()) {
            $query->withTrashed();
        }

        $categories = $query->latest()->paginate(15);
        $parentCategories = Category::whereNull('parent_id')->get();
        
        // Global Stats
        $totalCount    = Category::count();
        $activeCount   = Category::where('is_active', true)->count();
        $rootCount     = Category::whereNull('parent_id')->count();
        $inactiveCount = Category::where('is_active', false)->count();
        $deletedCount  = Category::onlyTrashed()->count();

        if (request()->wantsJson()) {
            return response()->json($categories);
        }

        return view('admin.categories.index', compact(
            'categories', 
            'parentCategories',
            'totalCount',
            'activeCount',
            'rootCount',
            'inactiveCount',
            'deletedCount'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'image' => 'nullable', // Support file upload or direct URL
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', config('filesystems.default'));
        } elseif ($request->filled('image') && filter_var($request->image, FILTER_VALIDATE_URL)) {
             $validated['image'] = $request->image;
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
            // Delete old local image
            if ($category->image && !filter_var($category->image, FILTER_VALIDATE_URL)) {
                Storage::disk(config('filesystems.default'))->delete($category->image);
            }
            $validated['image'] = $request->file('image')->store('categories', config('filesystems.default'));
        } elseif ($request->filled('image') && filter_var($request->image, FILTER_VALIDATE_URL)) {
             $validated['image'] = $request->image;
        }

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

    public function restore($id)
    {
        if (!auth()->user()->isSuperAdmin()) {
            return response()->json(['message' => 'Unauthorized. Only Super Admin can restore categories.'], 403);
        }

        $category = Category::withTrashed()->findOrFail($id);
        $category->restore();

        return response()->json([
            'message' => 'Category restored successfully!',
            'category' => $category->load('parent')
        ]);
    }
}
