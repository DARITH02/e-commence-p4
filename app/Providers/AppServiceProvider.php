<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

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
        view()->composer(['layouts.admin', 'admin.auth.*'], function ($view) {
            $settings = \App\Models\Setting::where('group', 'general')
                ->get()
                ->pluck('value', 'key')
                ->toArray();
            $view->with('admin_settings', $settings);

            if (auth()->check()) {
                $notifications = auth()->user()->unreadNotifications()->take(5)->get();
                $unreadCount = auth()->user()->unreadNotifications()->count();
                $view->with('admin_notifications', $notifications);
                $view->with('unread_notifications_count', $unreadCount);
            }
        });
    }
}
