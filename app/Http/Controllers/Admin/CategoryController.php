<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('quizzes')->latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:500',
            'color'       => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        Category::create($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', "Danh mục \"{$validated['name']}\" đã được tạo!");
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'        => "required|string|max:100|unique:categories,name,{$category->id}",
            'description' => 'nullable|string|max:500',
            'color'       => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $category->update($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Danh mục đã được cập nhật!');
    }

    public function destroy(Category $category)
    {
        if ($category->quizzes()->count() > 0) {
            return back()->with('error', 'Không thể xóa danh mục đang có quiz. Hãy xóa các quiz trước.');
        }

        $name = $category->name;
        $category->delete();

        return back()->with('success', "Danh mục \"{$name}\" đã được xóa.");
    }
}
