<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories for users.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $categories = Category::withCount('books')
            ->when($search, function($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->paginate(10);

        return view('user.categories.index', compact('categories', 'search'));
    }

    /**
     * Display the specified category for users.
     */
    public function show(Category $category)
    {
        $category->loadCount('books');
        return view('user.categories.show', compact('category'));
    }

    /**
     * Display a listing of categories for admin.
     */
    public function adminIndex(Request $request)
    {
        $search = $request->input('search');

        $categories = Category::withCount('books')
            ->when($search, function($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->paginate(10);

        return view('admin.categories.index', compact('categories', 'search'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified category for admin.
     */
    public function adminShow(Category $category)
    {
        $category->loadCount('books');
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$category->id,
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        if($category->books()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete category with associated books.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
