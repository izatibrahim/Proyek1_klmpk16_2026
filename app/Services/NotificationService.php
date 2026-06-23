<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;

class NotificationService
{
    /**
     * Cek & buat semua notifikasi yang relevan untuk user.
     * Dipanggil di DashboardController setiap kali user buka dashboard.
     */
    public function generateForUser(User $user): void
    {
        $this->checkDeadlineReminders($user);
        $this->checkHabitReminders($user);
        $this->checkAchievements($user);
    }

    // =====================
    // DEADLINE REMINDERS
    // =====================

    private function checkDeadlineReminders(User $user): void
    {
        $tomorrow = now()->addDay()->toDateString();
        $today    = now()->toDateString();

        // Tugas deadline besok yang belum selesai
        $user->todos()
            ->where('is_done', false)
            ->whereDate('deadline', $tomorrow)
            ->with('category')
            ->get()
            ->each(function ($todo) use ($user) {
                $key = "deadline_tomorrow_{$todo->todos_id}";

                if (!$this->alreadySent($user->id, $key)) {
                    Notification::create([
                        'user_id' => $user->id,
                        'type'    => 'deadline_reminder',
                        'title'   => '⏰ Deadline Besok!',
                        'message' => "Tugas \"{$todo->title}\" harus selesai besok!",
                        'icon'    => '⏰',
                        'link'    => route('todos.index'),
                        'data'    => ['key' => $key, 'todo_id' => $todo->todos_id],
                    ]);
                }
            });

        // Tugas overdue (lewat deadline) yang belum selesai
        $user->todos()
            ->where('is_done', false)
            ->whereDate('deadline', '<', $today)
            ->get()
            ->each(function ($todo) use ($user) {
                $key = "deadline_overdue_{$todo->todos_id}_" . now()->toDateString();

                if (!$this->alreadySent($user->id, $key)) {
                    Notification::create([
                        'user_id' => $user->id,
                        'type'    => 'deadline_reminder',
                        'title'   => '🚨 Tugas Terlambat!',
                        'message' => "Tugas \"{$todo->title}\" sudah melewati deadline!",
                        'icon'    => '🚨',
                        'link'    => route('todos.index'),
                        'data'    => ['key' => $key, 'todo_id' => $todo->todos_id],
                    ]);
                }
            });
    }

    // =====================
    // HABIT REMINDERS
    // =====================

    private function checkHabitReminders(User $user): void
    {
        // Kirim reminder kalau jam sudah > 18.00 dan habit harian belum dicek
        if (now()->hour < 18) return;

        $user->habits()
            ->where('frequency', 'daily')
            ->with('logs')
            ->get()
            ->each(function ($habit) use ($user) {
                $doneTodayCount = $habit->logs
                    ->filter(fn($l) => \Carbon\Carbon::parse($l->completed_at)->isToday())
                    ->count();

                if ($doneTodayCount === 0) {
                    $key = "habit_reminder_{$habit->habits_id}_" . now()->toDateString();

                    if (!$this->alreadySent($user->id, $key)) {
                        Notification::create([
                            'user_id' => $user->id,
                            'type'    => 'habit_reminder',
                            'title'   => '💪 Jangan Lupa Habit!',
                            'message' => "Kamu belum menyelesaikan habit \"{$habit->name}\" hari ini.",
                            'icon'    => '💪',
                            'link'    => route('habits.index'),
                            'data'    => ['key' => $key, 'habit_id' => $habit->habits_id],
                        ]);
                    }
                }
            });
    }

    // =====================
    // ACHIEVEMENT UNLOCKS
    // =====================

    private function checkAchievements(User $user): void
    {
        $user->habits()->with('logs')->get()->each(function ($habit) use ($user) {
            $streak = $habit->streak_count;

            $milestones = [3 => '3 hari', 7 => '1 minggu', 14 => '2 minggu', 30 => '1 bulan', 100 => '100 hari'];

            foreach ($milestones as $days => $label) {
                if ($streak === $days) {
                    $key = "achievement_streak_{$habit->habits_id}_{$days}";

                    if (!$this->alreadySent($user->id, $key)) {
                        Notification::create([
                            'user_id' => $user->id,
                            'type'    => 'achievement',
                            'title'   => '🏆 Pencapaian Baru!',
                            'message' => "Keren! Kamu sudah konsisten melakukan \"{$habit->name}\" selama {$label} berturut-turut!",
                            'icon'    => '🏆',
                            'link'    => route('habits.show', $habit->habits_id),
                            'data'    => ['key' => $key, 'habit_id' => $habit->habits_id, 'days' => $days],
                        ]);
                    }
                    break;
                }
            }
        });
    }

    // =====================
    // HELPER
    // =====================

    private function alreadySent(int $userId, string $key): bool
    {
        return Notification::where('user_id', $userId)
            ->whereJsonContains('data->key', $key)
            ->exists();
    }
}
