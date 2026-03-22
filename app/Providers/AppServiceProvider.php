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
        \Illuminate\Support\Facades\Blade::directive('km', function ($expression) {
            return "<?php 
                \$val = $expression;
                if (App::getLocale() === 'km') {
                    \$khmerDigits = ['០', '១', '២', '៣', '៤', '៥', '៦', '៧', '៨', '៩'];
                    \$arabicDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
                    echo str_replace(\$arabicDigits, \$khmerDigits, \$val);
                } else {
                    echo \$val;
                }
            ?>";
        });

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
