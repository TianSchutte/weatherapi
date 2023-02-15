<?php

namespace tian\weatherapi\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;

class WeatherProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // load necessary folders
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
        $this->loadViewsFrom(__DIR__ . '/../views', 'weatherapi');

        // Binds User Model to package
        $this->app->bind('User', function ($app) {
            return new User();
        });
    }
}
