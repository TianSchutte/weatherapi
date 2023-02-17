<?php

namespace Tian\Weatherapi\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Blade;
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
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'weatherapi');
        $this->loadViewsFrom(__DIR__ . '/../resources/views/components', 'weatherapi');

        // config folder
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'weatherapi');

        // Binds User Model to package
        $this->app->bind('User', function ($app) {
            return new User();
        });


    }
}
