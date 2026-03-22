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
        $query = Category::with(['parents', 'images'])->withCount(['products', 'children']);
        
        if (auth()->check() && auth()->user()->isSuperAdmin()) {
            $query->withTrashed();
        }

        $categories = $query->latest()->paginate(15);
        
        // Fetch all categories for parent selection
        $allCategories = Category::orderBy('name')->get();
        
        // Build a simple tree for the dropdown
        $parentCategories = $this->buildCategoryTree($allCategories);
        
        // Global Stats
        $totalCount    = Category::count();
        $activeCount   = Category::where('is_active', true)->count();
        $rootCount     = Category::doesntHave('parents')->count();
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

    /**
     * Build a flat list of categories with hierarchy prefixes
     */
    private function buildCategoryTree($categories, $parentId = null, $prefix = '', &$visited = [])
    {
        $result = [];
        $children = $categories->filter(function($category) use ($parentId) {
            $parents = $category->parents;
            if ($parentId === null) {
                return $parents->isEmpty();
            }
            return $parents->contains('id', $parentId);
        });
        
        foreach ($children as $category) {
            if (in_array($category->id, $visited)) continue; 
            
            $visited[] = $category->id;
            $category->displayName = $prefix . $category->name;
            $result[] = $category;
            $newPrefix = $prefix ? $prefix . $category->name . ' > ' : $category->name . ' > ';
            $result = array_merge($result, $this->buildCategoryTree($categories, $category->id, $newPrefix, $visited));
        }
        return $result;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|array',
            'parent_id.*' => 'exists:categories,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'image' => 'nullable', // Primary image
            'images' => 'nullable|array', // Gallery images
            'image_urls' => 'nullable|array', // Gallery URLs
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $parents = $validated['parent_id'] ?? [];
            unset($validated['parent_id']);

            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('categories', config('filesystems.default'));
            } elseif ($request->filled('image') && filter_var($request->image, FILTER_VALIDATE_URL)) {
                 $validated['image'] = $request->image;
            }

            $category = Category::create($validated);
            
            if (!empty($parents)) {
                $category->parents()->sync($parents);
            }

            // Handle Gallery Images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('categories', config('filesystems.default'));
                    $category->images()->create(['image_path' => $path]);
                }
            }

            if ($request->filled('image_urls')) {
                foreach ($request->image_urls as $url) {
                    if (!empty($url)) {
                        $category->images()->create(['image_path' => $url]);
                    }
                }
            }

            \Illuminate\Support\Facades\DB::commit();

            return response()->json([
                'message' => 'Category created successfully!',
                'category' => $category->load(['parents', 'images'])
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json(['message' => 'Failed to create category: ' . $e->getMessage()], 500);
        }
    }

    public function show(Category $category)
    {
        return response()->json($category->load(['parents', 'images']));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|array',
            'parent_id.*' => 'exists:categories,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'image' => 'nullable',
            'images' => 'nullable|array',
            'image_urls' => 'nullable|array',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']);
        
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $parents = $validated['parent_id'] ?? [];
            unset($validated['parent_id']);

            if ($request->hasFile('image')) {
                // Delete old local image
                if ($category->image && !filter_var($category->image, FILTER_VALIDATE_URL)) {
                    \Illuminate\Support\Facades\Storage::disk(config('filesystems.default'))->delete($category->image);
                }
                $validated['image'] = $request->file('image')->store('categories', config('filesystems.default'));
            } elseif ($request->filled('image') && filter_var($request->image, FILTER_VALIDATE_URL)) {
                 $validated['image'] = $request->image;
            }

            // Filter out self from parents to prevent circularity
            $parents = array_filter($parents, fn($id) => $id != $category->id);

            $category->update($validated);
            
            $category->parents()->sync($parents);

            // Handle New Gallery Images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('categories', config('filesystems.default'));
                    $category->images()->create(['image_path' => $path]);
                }
            }

            if ($request->filled('image_urls')) {
                foreach ($request->image_urls as $url) {
                    if (!empty($url)) {
                        $category->images()->create(['image_path' => $url]);
                    }
                }
            }

            \Illuminate\Support\Facades\DB::commit();

            return response()->json([
                'message' => 'Category updated successfully!',
                'category' => $category->load(['parents', 'images'])
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json(['message' => 'Failed to update category: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Category $category)
    {
        if ($category->children_many()->exists()) {
            return response()->json([
                'message' => 'Cannot delete a category that has subcategories. Please move or delete the subcategories first.'
            ], 422);
        }

        $category->delete();
        return response()->json(['message' => 'Category deleted successfully!']);
    }

    public function destroyImage(\App\Models\CategoryImage $image)
    {
        if ($image->image_path && !filter_var($image->image_path, FILTER_VALIDATE_URL)) {
            \Illuminate\Support\Facades\Storage::disk(config('filesystems.default'))->delete($image->image_path);
        }
        $image->delete();
        return response()->json(['message' => 'Image deleted successfully!']);
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
            'category' => $category->load('parents')
        ]);
    }
}
