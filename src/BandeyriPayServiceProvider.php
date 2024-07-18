<?php

namespace Javaabu\BandeyriPay;

use Illuminate\Support\ServiceProvider;

class BandeyriPayServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // declare publishes
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/bandeyri-pay.php' => config_path('bandeyri-pay.php'),
            ], 'bandeyri-pay-config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // merge package config with user defined config
        $this->mergeConfigFrom(__DIR__ . '/../config/bandeyri-pay-laravel.php', 'bandeyri-pay-laravel');
    }
}
