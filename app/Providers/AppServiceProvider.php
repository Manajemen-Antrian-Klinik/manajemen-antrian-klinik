<?php

namespace App\Providers;

use App\Mail\ResetPasswordMail;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;

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
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
        return url(route('password.reset', [
            'token' => $token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
    });

    ResetPassword::toMailUsing(function (object $notifiable, string $token) {
        $resetUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
        
        return (new ResetPasswordMail($notifiable, $resetUrl))->to($notifiable->getEmailForPasswordReset());
    });
    }
}