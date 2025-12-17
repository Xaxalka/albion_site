<?php

namespace App\Providers;

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
        View::composer('*', function ($view): void {
            $currentUser = auth()->user();

            $view->with('currentUser', $currentUser);
            $view->with('isAdmin', (bool) $currentUser?->isAdmin());
        });
    }
}
