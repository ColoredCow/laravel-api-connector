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
        $this->mergeConfigFrom(__DIR__ . '/../config/apiconnector.php', 'apiconnector');
    }

    /**
     * Register APIConnector's migration files.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__ . '/../config/apiconnector.php' => config_path('apiconnector.php'),
        ], 'apiconnector-config');

        // $this->publishes([
        //     __DIR__ . '/../database/migrations/create_randomables_table.php' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_randomables_table.php'),
        // ], 'randomable-migrations');

    }
}
