<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\HabitLog;
use App\Services\StreakService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HabitController extends Controller
{
    public function __construct(protected StreakService $streakService) {}

    public function index()
    {
        $habits = auth()->user()
            ->habits()
            ->with(['logs' => fn($q) => $q->orderByDesc('completed_at')])
            ->get();

        return view('habits.index', compact('habits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'frequency' => 'required|in:daily,weekly',
        ]);

        auth()->user()->habits()->create([
            'name'           => $request->name,
            'frequency'      => $request->frequency,
            'streak_count'   => 0,
            'longest_streak' => 0,
        ]);

        return back()->with('success', 'Habit berhasil ditambahkan! Mulai dari hari ini ya 💪');
    }

    public function check(Request $request, Habit $habit)
    {
        $this->authorize('update', $habit);

        if (!$this->streakService->canCheckToday($habit)) {
            return back()->with('error',
                $habit->frequency === 'daily'
                    ? 'Kamu sudah menyelesaikan habit ini hari ini! 🎉'
                    : 'Kamu sudah menyelesaikan habit ini pekan ini! 🎉'
            );
        }

        $request->validate(['note' => 'nullable|string|max:255']);

        $habit->logs()->create([
            'completed_at' => today(),
            'note'         => $request->note,
        ]);

        $habit->load('logs');
        $this->streakService->updateStreak($habit);
        $streak = $habit->fresh()->streak_count;

        $message = match(true) {
            $streak >= 30 => "🔥 Luar biasa! Streak {$streak} hari! Kamu konsisten banget!",
            $streak >= 7  => "⚡ Mantap! Sudah {$streak} hari berturut-turut!",
            $streak >= 3  => "✅ Bagus! Streak {$streak} hari, terus pertahankan!",
            default       => '✅ Habit berhasil dicatat!',
        };

        return back()->with('success', $message);
    }

    public function uncheck(Habit $habit)
    {
        $this->authorize('update', $habit);

        $deleted = $habit->logs()->whereDate('completed_at', today())->delete();

        if ($deleted) {
            $habit->load('logs');
            $this->streakService->updateStreak($habit);
            return back()->with('success', 'Check-in hari ini dibatalkan.');
        }

        return back()->with('error', 'Tidak ada check-in hari ini yang bisa dibatalkan.');
    }

    public function show(Habit $habit)
    {
        $this->authorize('view', $habit);
        $habit->load('logs');

        $month      = request('month', now()->month);
        $year       = request('year', now()->year);
        $carbonDate = Carbon::createFromDate($year, $month, 1);

        $monthLogs = $habit->logs()
            ->whereYear('completed_at', $year)
            ->whereMonth('completed_at', $month)
            ->pluck('completed_at')
            ->map(fn($d) => Carbon::parse($d)->day)
            ->toArray();

        $stats = [
            'current_streak'   => $habit->streak_count,
            'longest_streak'   => $habit->longest_streak,
            'total_done'       => $habit->total_done,
            'rate_30'          => $this->streakService->completionRate($habit, 30),
            'rate_7'           => $this->streakService->completionRate($habit, 7),
            'days_since_start' => $habit->started_at ? $habit->started_at->diffInDays(today()) + 1 : 1,
        ];

        $recentLogs = $habit->logs()->orderByDesc('completed_at')->take(20)->get();

        $prevMonth = $carbonDate->copy()->subMonth();
        $nextMonth = $carbonDate->copy()->addMonth();
        $canGoNext = $carbonDate->lt(now()->startOfMonth());

        return view('habits.show', compact(
            'habit', 'monthLogs', 'carbonDate',
            'stats', 'recentLogs',
            'prevMonth', 'nextMonth', 'canGoNext'
        ));
    }

    public function update(Request $request, Habit $habit)
    {
        $this->authorize('update', $habit);
        $request->validate([
            'name'      => 'required|string|max:255',
            'frequency' => 'required|in:daily,weekly',
        ]);
        $habit->update($request->only('name', 'frequency'));
        return back()->with('success', 'Habit berhasil diperbarui!');
    }

    public function destroy(Habit $habit)
    {
        $this->authorize('delete', $habit);
        $habit->delete();
        return back()->with('success', 'Habit dihapus.');
    }

    public function deleteLog(Habit $habit, HabitLog $log)
    {
        $this->authorize('update', $habit);

        if ($log->habit_id !== $habit->habits_id) abort(403);

        $log->delete();
        $habit->load('logs');
        $this->streakService->updateStreak($habit);

        return back()->with('success', 'Log dihapus dan streak diperbarui.');
    }
}
