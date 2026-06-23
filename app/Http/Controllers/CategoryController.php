<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() 
    {
        $categories = auth()->user()->categories()->withCount('todos')->get();
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) 
    {
        $request->validate([
            'name'  => 'required|string|max:100',
            'color' => 'required|string|size:7',
        ]);
        auth()->user()->categories()->create($request->only('name', 'color'));
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        abort_if($category->user_id !== auth()->id(), 404);
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category) 
    {
        abort_if($category->user_id !== auth()->id(), 404);
        $request->validate(['name' => 'required|string|max:100', 'color' => 'required|string|size:7']);
        $category->update($request->only('name', 'color'));
        return redirect()->route('categories.index')->with('success', 'Kategori diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category) 
    {
        abort_if($category->user_id !== auth()->id(), 404);
        $category->delete();
        return back()->with('success', 'Kategori dihapus!');
    }
}
