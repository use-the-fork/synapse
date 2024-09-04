<?php

declare(strict_types=1);

namespace UseTheFork\Synapse;

use Illuminate\Support\ServiceProvider;

class SynapseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/synapse.php' => config_path('synapse.php'),
        ]);

        $this->loadMigrationsFrom([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ]);

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'synapse');
    }

    /**
     * Register the service provider.
     */
    public function register() {}
}
