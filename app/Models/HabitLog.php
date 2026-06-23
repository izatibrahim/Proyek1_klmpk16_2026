<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HabitLog extends Model
{
    protected $primaryKey = 'habit_logs_id';
    protected $fillable = ['habit_id', 'completed_at', 'note'];
    protected $casts = ['completed_at' => 'date'];

    public function habit() {
        return $this->belongsTo(Habit::class, 'habit_id', 'habits_id');
    }
}
