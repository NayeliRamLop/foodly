<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;


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
        Paginator::useBootstrapFour();

        View::composer('*', function ($view) {
            if (Auth::check()) {
                $view->with('unreadNotificationsCount', Auth::user()->unreadNotifications()->count());
            } else {
                $view->with('unreadNotificationsCount', 0);
            }
        });

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
return (new MailMessage)
->subject('Verify Email Address')
->line('Click the button below to verify your email address.')
->action('Verify Email Address', $url);
});

    }
}
