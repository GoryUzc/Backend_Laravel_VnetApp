<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
         if ($this->app->isLocal()) {
        // Configuración para desarrollo
        \URL::forceRootUrl(config('app.url'));
        \URL::forceScheme('http');
                }
    }
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}