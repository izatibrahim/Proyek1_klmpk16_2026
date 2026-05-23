<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    protected $primaryKey = 'todos_id';
    protected $fillable = ['user_id', 'category_id', 'title', 'description', 'deadline', 'is_done'];
    protected $casts = ['is_done' => 'boolean', 'deadline' => 'date'];

    public function category() {
        return $this->belongsTo(Category::class, 'category_id', 'categories_id');
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
}
