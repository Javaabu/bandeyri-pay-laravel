<?php

namespace Javaabu\{YourPackage}\Tests\TestSupport\Providers;

use Illuminate\Support\ServiceProvider;

class TestServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadMigrationsFrom([
            __DIR__ . '/../database',
        ]);
    }

    /**
     * Register the application services.
     */
    public function register()
    {

    }
}
