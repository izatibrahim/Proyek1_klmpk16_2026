<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;
use App\Services\StreakService;
use App\Services\NotificationService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Daftarkan service agar bisa di-inject ke controller
        $this->app->singleton(StreakService::class);
        $this->app->singleton(NotificationService::class);
    }

    public function boot(): void
    {
        // Locale Indonesia untuk Carbon (tanggal dalam bahasa Indonesia)
        Carbon::setLocale('id');
        App::setLocale('id');
    }
}
