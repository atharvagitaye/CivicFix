<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(\App\Models\SubCategory::with('category')->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:sub_categories,name',
            'category_id' => 'required|exists:categories,id',
        ]);
        $subCategory = \App\Models\SubCategory::create($validated);
        return response()->json($subCategory, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subCategory = \App\Models\SubCategory::with('category')->findOrFail($id);
        return response()->json($subCategory);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $subCategory = \App\Models\SubCategory::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:sub_categories,name,' . $subCategory->id,
            'category_id' => 'required|exists:categories,id',
        ]);
        $subCategory->update($validated);
        return response()->json($subCategory);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subCategory = \App\Models\SubCategory::findOrFail($id);
        $subCategory->delete();
        return response()->json(['message' => 'Subcategory deleted']);
    }
}
