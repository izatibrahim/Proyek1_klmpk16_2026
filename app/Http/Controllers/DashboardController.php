<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\Habit;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get today's todos
        $todaysTodos = Todo::where('user_id', $user->id)
            ->whereDate('deadline', Carbon::today())
            ->where('is_done', false)
            ->get();
        
        // Get all todos for current month
        $currentMonth = Carbon::now();
        $monthTodos = Todo::where('user_id', $user->id)
            ->whereYear('deadline', $currentMonth->year)
            ->whereMonth('deadline', $currentMonth->month)
            ->get();
        
        // Get next 7 days todos
        $upcomingTodos = Todo::where('user_id', $user->id)
            ->whereBetween('deadline', [Carbon::today(), Carbon::today()->addDays(7)])
            ->where('is_done', false)
            ->orderBy('deadline')
            ->get();
        
        // Get user's habits
        $habits = Habit::where('user_id', $user->id)->get();
        
        // Get all users for team members display
        $teamMembers = User::limit(8)->get();
        
        // Get today's specific todos with more details
        $todayTodos = Todo::where('user_id', $user->id)
            ->whereDate('deadline', Carbon::today())
            ->orderBy('is_done')
            ->get();
        
        return view('dashboard', [
            'todaysTodosCount' => $todaysTodos->count(),
            'upcomingTodos' => $upcomingTodos,
            'monthTodos' => $monthTodos,
            'habits' => $habits,
            'teamMembers' => $teamMembers,
            'todayTodos' => $todayTodos,
            'currentMonth' => $currentMonth,
        ]);
    }
}
