<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Habit extends Model
{
    use HasFactory;

    protected $primaryKey = 'habits_id';

    protected $fillable = [
        'user_id',
        'name',
        'frequency',
        'streak_count',
        'longest_streak',
        'started_at',
    ];

    protected $casts = [
        'started_at' => 'date',
    ];

    // =====================
    // RELASI
    // =====================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->hasMany(HabitLog::class, 'habit_id', 'habits_id');
    }

    // =====================
    // ACCESSOR / HELPER
    // =====================

    /** Apakah sudah dicek hari ini / pekan ini? */
    public function getIsCheckedTodayAttribute(): bool
    {
        if ($this->frequency === 'daily') {
            return $this->logs->contains(
                fn($l) => Carbon::parse($l->completed_at)->isToday()
            );
        }
        $weekStart = now()->startOfWeek(Carbon::MONDAY);
        $weekEnd   = now()->endOfWeek(Carbon::SUNDAY);
        return $this->logs->contains(
            fn($l) => Carbon::parse($l->completed_at)->between($weekStart, $weekEnd)
        );
    }

    /** Jumlah total hari unik dikerjakan. */
    public function getTotalDoneAttribute(): int
    {
        return $this->logs
            ->pluck('completed_at')
            ->map(fn($d) => Carbon::parse($d)->toDateString())
            ->unique()
            ->count();
    }

    protected static function booted(): void
    {
        static::creating(function (Habit $habit) {
            $habit->started_at = $habit->started_at ?? today();
        });
    }
}
