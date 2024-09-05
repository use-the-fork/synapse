<?php

declare(strict_types=1);

namespace UseTheFork\Synapse;

use Illuminate\Support\ServiceProvider;

class SynapseServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * In this method, we publish the Synapse configuration file to the application's config directory,
     * load Synapse's database migrations, and register Synapse's views.
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
}
