<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $primaryKey = 'categories_id';
    protected $fillable = ['name', 'color', 'user_id'];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Relasi ke Todo
    public function todos()
    {
        return $this->hasMany(Todo::class, 'category_id', 'categories_id');
    }
}