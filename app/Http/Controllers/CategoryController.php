<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * 🗂 List all categories for management.
     */
    public function index()
    {
        $categories = Category::orderBy('name')->get();
        return view('categories.index', compact('categories'));
    }

    /**
     * ➕ Create Category Form
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * 💾 Store Category
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        // Note: Slug generation is handled by the Model's boot method
        Category::create($data);

        return redirect()->route('categories.index')->with('success', 'Category added successfully ✅');
    }

    /**
     * 👁 SHOW CATEGORY ARCHIVE
     */
    public function show($slug)
    {
        $category = Category::where('slug', $slug)
            ->orWhere('id', $slug)
            ->firstOrFail();

        $tickets = Ticket::where('category_id', $category->id)
            ->whereIn('status', ['Closed', 'Condemned'])
            ->with(['assignee'])
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        return view('categories.show', compact('category', 'tickets'));
    }

    /**
     * ✏ Edit Category
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * 🔄 Update Category
     */
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            // unique:table,column,except,idColumn
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        // If your edit form doesn't have a slug input, don't require it in validation.
        // Our Model boot method handles slug updates automatically when the name changes.
        $category->update($data);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully ✅');
    }

    /**
     * 🗑 Delete Category
     */
    public function destroy(Category $category)
    {
        // Optional: Check if tickets exist before deleting
        if ($category->tickets()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete category that has tickets associated with it.');
        }

        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted successfully ❌');
    }
}