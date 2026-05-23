<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Todo;
use App\Models\Habit;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create multiple users
        User::factory(8)->create();
        
        $user = User::factory()->create([
            'name' => 'Antony Jacob',
            'email' => 'test@example.com',
        ]);

        // Create categories
        $categories = Category::create([
            'user_id' => $user->id,
            'name' => 'Work',
        ]);
        
        Category::create([
            'user_id' => $user->id,
            'name' => 'Personal',
        ]);

        // Create todos for today
        Todo::create([
            'user_id' => $user->id,
            'category_id' => $categories->id,
            'title' => 'Research Plan',
            'description' => 'Complete market research for Q3',
            'deadline' => Carbon::today(),
            'is_done' => false,
        ]);

        Todo::create([
            'user_id' => $user->id,
            'category_id' => $categories->id,
            'title' => 'Team Meeting',
            'description' => 'Discussion of tasks for the month',
            'deadline' => Carbon::today(),
            'is_done' => false,
        ]);

        Todo::create([
            'user_id' => $user->id,
            'category_id' => $categories->id,
            'title' => 'Design Review',
            'description' => 'Review new UI mockups',
            'deadline' => Carbon::today(),
            'is_done' => false,
        ]);

        // Create upcoming todos
        for ($i = 1; $i <= 7; $i++) {
            Todo::create([
                'user_id' => $user->id,
                'category_id' => $categories->id,
                'title' => 'Task ' . $i,
                'description' => 'Upcoming task description',
                'deadline' => Carbon::today()->addDays($i),
                'is_done' => false,
            ]);
        }

        // Create habits
        Habit::create([
            'user_id' => $user->id,
            'name' => 'Morning Exercise',
            'frequency' => 'daily',
            'streak_count' => 12,
        ]);

        Habit::create([
            'user_id' => $user->id,
            'name' => 'Reading',
            'frequency' => 'daily',
            'streak_count' => 8,
        ]);

        Habit::create([
            'user_id' => $user->id,
            'name' => 'Meditation',
            'frequency' => 'daily',
            'streak_count' => 5,
        ]);
    }
}
