<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Habit extends Model
{
    protected $primaryKey = 'habits_id';
    protected $fillable = ['user_id', 'name', 'frequency', 'streak_count'];

    public function logs() {
        return $this->hasMany(HabitLog::class, 'habit_id', 'habits_id');
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
}
