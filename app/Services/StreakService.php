<?php

namespace App\Services;

use App\Models\Habit;
use App\Models\HabitLog;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class StreakService
{
    /**
     * Hitung current streak dari tanggal sekarang mundur ke belakang.
     * Daily  → harus berurutan tiap hari tanpa bolong.
     * Weekly → harus ada minimal 1 log tiap pekan berurutan.
     */
    public function calculateCurrentStreak(Habit $habit): int
    {
        $logs = $habit->logs()
            ->orderByDesc('completed_at')
            ->pluck('completed_at')
            ->map(fn($d) => Carbon::parse($d)->startOfDay())
            ->unique()
            ->values();

        if ($logs->isEmpty()) return 0;

        return $habit->frequency === 'weekly'
            ? $this->weeklyStreak($logs)
            : $this->dailyStreak($logs);
    }

    /**
     * Hitung longest streak sepanjang masa.
     */
    public function calculateLongestStreak(Habit $habit): int
    {
        $logs = $habit->logs()
            ->orderBy('completed_at')
            ->pluck('completed_at')
            ->map(fn($d) => Carbon::parse($d)->startOfDay())
            ->unique()
            ->values();

        if ($logs->isEmpty()) return 0;

        return $habit->frequency === 'weekly'
            ? $this->longestWeeklyStreak($logs)
            : $this->longestDailyStreak($logs);
    }

    /**
     * Hitung completion rate (30 hari terakhir).
     * Daily  → target 30, Weekly → target ~4.
     */
    public function completionRate(Habit $habit, int $days = 30): float
    {
        $since = now()->subDays($days)->startOfDay();

        $doneDates = $habit->logs()
            ->where('completed_at', '>=', $since)
            ->pluck('completed_at')
            ->map(fn($d) => Carbon::parse($d)->startOfDay()->toDateString())
            ->unique()
            ->count();

        $target = $habit->frequency === 'weekly'
            ? (int) ceil($days / 7)
            : $days;

        return $target > 0 ? round(min(100, ($doneDates / $target) * 100), 1) : 0;
    }

    /**
     * Apakah habit boleh di-check sekarang?
     * Daily  → 1x per hari.
     * Weekly → 1x per pekan (Senin–Minggu).
     */
    public function canCheckToday(Habit $habit): bool
    {
        if ($habit->frequency === 'daily') {
            return !$habit->logs()
                ->whereDate('completed_at', today())
                ->exists();
        }

        // Weekly: cek apakah sudah ada log di pekan ini (Sen–Min)
        $weekStart = now()->startOfWeek(Carbon::MONDAY);
        $weekEnd   = now()->endOfWeek(Carbon::SUNDAY);

        return !$habit->logs()
            ->whereBetween('completed_at', [$weekStart, $weekEnd])
            ->exists();
    }

    /**
     * Recalculate & update streak_count di DB.
     * Dipanggil setiap kali log baru ditambahkan.
     */
    public function updateStreak(Habit $habit): void
    {
        $current = $this->calculateCurrentStreak($habit);
        $longest = max($this->calculateLongestStreak($habit), $habit->longest_streak ?? 0);

        $habit->update([
            'streak_count'   => $current,
            'longest_streak' => $longest,
        ]);
    }

    // ========================
    // PRIVATE HELPERS
    // ========================

    private function dailyStreak(Collection $logs): int
    {
        $streak   = 0;
        $expected = now()->startOfDay();

        // Boleh ada gap 1 hari (hari ini belum dicek = mulai dari kemarin)
        if ($logs->first()->ne($expected)) {
            $expected = $expected->subDay();
        }

        foreach ($logs as $log) {
            if ($log->eq($expected)) {
                $streak++;
                $expected = $expected->subDay();
            } else {
                break; // ada bolong → streak berhenti
            }
        }

        return $streak;
    }

    private function longestDailyStreak(Collection $logs): int
    {
        $longest = 1;
        $current = 1;

        for ($i = 1; $i < $logs->count(); $i++) {
            $diff = $logs[$i - 1]->diffInDays($logs[$i]);
            if ($diff === 1) {
                $current++;
                $longest = max($longest, $current);
            } else {
                $current = 1;
            }
        }

        return $longest;
    }

    private function weeklyStreak(Collection $logs): int
    {
        $streak      = 0;
        $currentWeek = now()->startOfWeek(Carbon::MONDAY);

        // Cek mundur tiap pekan
        for ($w = 0; $w < 52; $w++) {
            $weekStart = $currentWeek->copy()->subWeeks($w);
            $weekEnd   = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);

            $hasLog = $logs->first(fn($d) => $d->between($weekStart, $weekEnd));

            if ($hasLog) {
                $streak++;
            } else {
                // Pekan ini (w=0) boleh kosong kalau hari ini masih awal pekan
                if ($w === 0 && now()->dayOfWeek === Carbon::MONDAY) continue;
                break;
            }
        }

        return $streak;
    }

    private function longestWeeklyStreak(Collection $logs): int
    {
        if ($logs->isEmpty()) return 0;

        // Ambil semua pekan unik yang punya log
        $weeks = $logs
            ->map(fn($d) => $d->startOfWeek(Carbon::MONDAY)->toDateString())
            ->unique()
            ->sort()
            ->values();

        $longest = 1;
        $current = 1;

        for ($i = 1; $i < $weeks->count(); $i++) {
            $prev = Carbon::parse($weeks[$i - 1]);
            $curr = Carbon::parse($weeks[$i]);

            if ($prev->diffInWeeks($curr) === 1) {
                $current++;
                $longest = max($longest, $current);
            } else {
                $current = 1;
            }
        }

        return $longest;
    }
}
