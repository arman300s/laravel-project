<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('active', true)
            ->latest()
            ->paginate(10);

        return view('user.categories.index', compact('categories'));
    }

    public function show(Category $category)
    {
        if (!$category->active) {
            abort(404);
        }

        return view('user.categories.show', compact('category'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search');

        $categories = Category::where('active', true)
            ->when($search, function($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->paginate(10)
            ->appends(['search' => $search]);

        return view('user.categories.index', compact('categories', 'search'));
    }
}
