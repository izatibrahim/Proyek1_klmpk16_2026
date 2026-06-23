<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Todo;
use App\Models\Category;
use App\Models\Habit;
use App\Policies\TodoPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\HabitPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Daftarkan semua policy di sini.
     */
    protected $policies = [
        Todo::class     => TodoPolicy::class,
        Category::class => CategoryPolicy::class,
        Habit::class    => HabitPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
