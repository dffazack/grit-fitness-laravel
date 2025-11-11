<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;
use App\Models\Notification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share active notifications with all views
        View::composer('*', function ($view) {
            try {
                $activeNotifications = Notification::active()->orderBy('created_at', 'desc')->get();
                $view->with('activeNotifications', $activeNotifications);
            } catch (\Exception $e) {
                // If the database or table doesn't exist yet (e.g., during migration), don't crash.
                $view->with('activeNotifications', collect());
            }
        });
    }
}
