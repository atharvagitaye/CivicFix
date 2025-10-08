<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::withCount(['subCategories', 'issues'])
            ->latest()
            ->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Category::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category->loadCount(['subCategories', 'issues']);
        $category->load(['subCategories.issues', 'issues.user']);
        
        // Get recent issues for this category
        $recentIssues = $category->issues()
            ->with(['user', 'subcategory'])
            ->latest()
            ->take(10)
            ->get();
        
        return view('admin.categories.show', compact('category', 'recentIssues'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Check if category has issues
        if ($category->issues()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete category that has associated issues.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully!');
    }

    /**
     * Show sub-categories for a category.
     */
    public function subCategories(Category $category)
    {
        $subCategories = $category->subCategories()
            ->withCount('issues')
            ->latest()
            ->paginate(15);

        return view('admin.categories.sub-categories', compact('category', 'subCategories'));
    }

    /**
     * Store a new sub-category.
     */
    public function storeSubCategory(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:sub_categories,name',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        SubCategory::create([
            'category_id' => $category->id,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->back()
            ->with('success', 'Sub-category created successfully!');
    }

    /**
     * Update a sub-category.
     */
    public function updateSubCategory(Request $request, Category $category, SubCategory $subCategory)
    {
        // Ensure the sub-category belongs to the category
        if ($subCategory->category_id !== $category->id) {
            return redirect()->back()
                ->with('error', 'Sub-category does not belong to this category.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:sub_categories,name,' . $subCategory->id,
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $subCategory->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->back()
            ->with('success', 'Sub-category updated successfully!');
    }

    /**
     * Delete a sub-category.
     */
    public function destroySubCategory(Category $category, SubCategory $subCategory)
    {
        // Ensure the sub-category belongs to the category
        if ($subCategory->category_id !== $category->id) {
            return redirect()->back()
                ->with('error', 'Sub-category does not belong to this category.');
        }

        // Check if sub-category has any issues
        $issuesCount = $subCategory->issues()->count();
        if ($issuesCount > 0) {
            return redirect()->back()
                ->with('error', "Cannot delete sub-category. It has {$issuesCount} associated issues.");
        }

        $subCategory->delete();

        return redirect()->back()
            ->with('success', 'Sub-category deleted successfully!');
    }
}
