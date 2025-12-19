<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        RateLimiter::for('login', function (Request $request) {
            $login = strtolower((string) $request->input('login'));

            return [
                Limit::perMinutes(15, 10)->by($request->ip()),
                Limit::perMinutes(15, 10)->by($request->ip().'|'.$login),
            ];
        });

        RateLimiter::for('register', function (Request $request) {
            return Limit::perMinutes(15, 10)->by($request->ip());
        });

        View::composer('*', function ($view): void {
            $currentUser = auth()->user();

            $view->with('currentUser', $currentUser);
            $view->with('isAdmin', (bool) $currentUser?->isAdmin());
        });
    }
}
