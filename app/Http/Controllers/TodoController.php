<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) 
    {
        $query = auth()->user()->todos()->with('category')->orderBy('deadline');

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->status === 'done')    $query->where('is_done', true);
        if ($request->status === 'pending') $query->where('is_done', false);

        $todos      = $query->get();
        $categories = auth()->user()->categories()->get();
        return view('todos.index', compact('todos', 'categories'));
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
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,categories_id',
            'deadline'    => 'nullable|date',
        ]);
        auth()->user()->todos()->create($request->all());
        return back()->with('success', 'Tugas ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Todo $todo)
    {
        abort_if($todo->user_id !== auth()->id(), 404);
        return view('todos.edit', compact('todo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Todo $todo) 
    {
        abort_if($todo->user_id !== auth()->id(), 404);
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,categories_id',
            'deadline'    => 'nullable|date',
        ]);
        $todo->update($request->all());
        return back()->with('success', 'Tugas diperbarui!');
    }

    public function toggleDone(Todo $todo) 
    {
        abort_if($todo->user_id !== auth()->id(), 404);
        $todo->update(['is_done' => !$todo->is_done]);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo) 
    {
        abort_if($todo->user_id !== auth()->id(), 404);
        $todo->delete();
        return back()->with('success', 'Tugas dihapus!');
    }

}
