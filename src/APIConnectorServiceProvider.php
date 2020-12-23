<?php

namespace ColoredCow\LaravelAPIConnector;

use Illuminate\Support\ServiceProvider;

class APIConnectorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->registerMigrations();
        }
    }

    public function register()
    {
        //
    }

    /**
     * Register APIConnector's migration files.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
