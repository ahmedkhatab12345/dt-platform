<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read categories')->only('index');
        $this->middleware('permission:create categories')->only(['create', 'store']);
        $this->middleware('permission:update categories')->only(['edit', 'update']);
        $this->middleware('permission:delete categories')->only('destroy');
    }

    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Category::create($request->only('name', 'description'));

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($request->only('name', 'description'));

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->users()->count() > 0) {
            return redirect()->route('categories.index')
                ->with('error', 'This category cannot be deleted because it is associated with users.');
        }

        $category->delete();
        return redirect()->route('categories.index')->with('success','Category deleted successfully.');
    }
}
