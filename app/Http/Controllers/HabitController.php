<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use Illuminate\Http\Request;

class HabitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() 
    {
        $habits = auth()->user()->habits()->with('logs')->get();
        return view('habits.index', compact('habits'));
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
            'name'      => 'required|string|max:255',
            'frequency' => 'required|in:daily,weekly',
        ]);
        auth()->user()->habits()->create($request->only('name', 'frequency'));
        return back()->with('success', 'Habit ditambahkan!');
    }

public function check(Habit $habit) 
{
        abort_if($habit->user_id !== auth()->id(), 404);
        $today = now()->toDateString();

        // Cek apakah sudah di-check hari ini
        $alreadyDone = $habit->logs()->whereDate('completed_at', $today)->exists();

        if (!$alreadyDone) {
            $habit->logs()->create(['completed_at' => $today]);

            // Hitung streak
            $lastLog = $habit->logs()->orderByDesc('completed_at')->skip(1)->first();
            $isStreak = $lastLog && $lastLog->completed_at->isYesterday();
            $habit->update(['streak_count' => $isStreak ? $habit->streak_count + 1 : 1]);

        }
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Habit $habit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Habit $habit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Habit $habit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Habit $habit) 
    {
        abort_if($habit->user_id !== auth()->id(), 404);
        $habit->delete();
        return back()->with('success', 'Habit dihapus!');
    }
}
