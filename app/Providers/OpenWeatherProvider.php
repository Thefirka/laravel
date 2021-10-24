<?php

namespace App\Providers;

use App\Services\CurrentWeather;
use App\Services\IOpenWeather;
use Illuminate\Support\ServiceProvider;

class OpenWeatherProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IOpenWeather::class, CurrentWeather::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
