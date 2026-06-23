<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\Habit;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use App\Services\NotificationService;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    public function __construct(protected NotificationService $notifService) {}

        public function index()
    {
        $user = auth()->user();

        // Generate notifikasi otomatis, hanya jika tabel tersedia
        if (Schema::hasTable('notifications')) {
            $this->notifService->generateForUser($user);
        }

        // ===== TODOS =====
        $allTodos   = $user->todos()->with('category')->get();
        $doneTodos  = $allTodos->where('is_done', true);
        $pending    = $allTodos->where('is_done', false);
        $overdue    = $pending->filter(fn($t) => $t->deadline && $t->deadline->isPast());
        $todayTodos = $allTodos->where('is_done', false);

        // ===== HABITS =====
        $habits = $user->habits()->with([
            'logs' => fn($q) => $q->orderByDesc('completed_at')
        ])->get();

        $habitsCheckedToday = $habits->filter(fn($h) => $h->is_checked_today);

        // ===== CHART 1: Todo completion per hari — 7 hari terakhir =====
        $last7Days   = collect(range(6, 0))->map(fn($i) => now()->subDays($i)->toDateString());
        $donePerDay  = $user->todos()
            ->where('is_done', true)
            ->whereNotNull('updated_at')
            ->where('updated_at', '>=', now()->subDays(6)->startOfDay())
            ->get()
            ->groupBy(fn($t) => $t->updated_at->toDateString())
            ->map->count();

        $todoChartLabels = $last7Days->map(fn($d) =>
            Carbon::parse($d)->translatedFormat('D')
        )->values()->toArray();

        $todoChartData = $last7Days->map(fn($d) =>
            $donePerDay[$d] ?? 0
        )->values()->toArray();

        // ===== CHART 2: Habit check-in per hari — 7 hari terakhir =====
        $habitLogsPerDay = DB::table('habit_logs')
            ->join('habits', 'habit_logs.habit_id', '=', 'habits.habits_id')
            ->where('habits.user_id', $user->id)
            ->where('habit_logs.completed_at', '>=', now()->subDays(6)->startOfDay())
            ->selectRaw('DATE(habit_logs.completed_at) as date, COUNT(DISTINCT habit_logs.habit_id) as cnt')
            ->groupBy('date')
            ->pluck('cnt', 'date');

        $habitChartData = $last7Days->map(fn($d) =>
            (int) ($habitLogsPerDay[$d] ?? 0)
        )->values()->toArray();

        // ===== CHART 3: Todo per kategori =====
        $categoryStats = $user->categories()
            ->withCount(['todos', 'todos as done_count' => fn($q) => $q->where('is_done', true)])
            ->get();

        // ===== STATISTIK RINGKASAN =====
        $completionPct = $allTodos->count() > 0
            ? round($doneTodos->count() / $allTodos->count() * 100)
            : 0;

        $bestStreak = $habits->max('streak_count') ?? 0;

        // Unread notifications count
        $unreadCount = Schema::hasTable('notifications')
            ? $user->notifications()->unread()->count()
            : 0;

        return view('dashboard', compact(
            'allTodos', 'doneTodos', 'pending', 'overdue', 'todayTodos',
            'habits', 'habitsCheckedToday',
            'todoChartLabels', 'todoChartData', 'habitChartData',
            'categoryStats', 'completionPct', 'bestStreak', 'unreadCount'
        ));
    }
}
