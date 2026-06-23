<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\HabitController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

    // Categories
    Route::middleware(['auth'])->group(function () {
    Route::resource('categories', CategoryController::class);

    // Todos
    Route::get('/todos', [TodoController::class, 'index'])->name('todos.index');
    Route::get('/todos/create', [TodoController::class, 'create'])->name('todos.create');
    Route::get('/todos/{todo}/edit', [TodoController::class, 'edit'])->name('todos.edit');
    Route::post('/todos', [TodoController::class, 'store'])->name('todos.store');
    Route::put('/todos/{todo}', [TodoController::class, 'update'])->name('todos.update');
    Route::delete('/todos/{todo}', [TodoController::class, 'destroy'])->name('todos.destroy');
    Route::patch('/todos/{todo}/toggle', [TodoController::class, 'toggleDone'])->name('todos.toggle');

    // Habits
    Route::get('/habits',                                   [HabitController::class, 'index'])     ->name('habits.index');
    Route::post('/habits',                                  [HabitController::class, 'store'])     ->name('habits.store');
    Route::get('/habits/{habit}',                           [HabitController::class, 'show'])      ->name('habits.show');
    Route::put('/habits/{habit}',                           [HabitController::class, 'update'])    ->name('habits.update');
    Route::delete('/habits/{habit}',                        [HabitController::class, 'destroy'])   ->name('habits.destroy');
    Route::patch('/habits/{habit}/check',                   [HabitController::class, 'check'])     ->name('habits.check');
    Route::patch('/habits/{habit}/uncheck',                 [HabitController::class, 'uncheck'])   ->name('habits.uncheck');
    Route::delete('/habits/{habit}/logs/{log}',             [HabitController::class, 'deleteLog']) ->name('habits.logs.destroy');

    // Notifications
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/',              [NotificationController::class, 'index'])       ->name('index');
        Route::get('/unread-count',  [NotificationController::class, 'unreadCount']) ->name('unreadCount');
        Route::patch('/mark-all',    [NotificationController::class, 'markAllRead']) ->name('markAllRead');
        Route::patch('/{notification}/read', [NotificationController::class, 'markRead'])  ->name('markRead');
        Route::delete('/{notification}',     [NotificationController::class, 'destroy'])   ->name('destroy');
    });

});
require __DIR__.'/auth.php';
