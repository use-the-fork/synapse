<?php

declare(strict_types=1);

namespace UseTheFork\Synapse;

use Illuminate\Support\ServiceProvider;
use UseTheFork\Synapse\Console\Commands\SynapseInstall;

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
        if ($this->app->runningInConsole()) {
            $this->commands([
                                SynapseInstall::class,
                            ]);
        }

        $this->publishes([
            __DIR__.'/../config/synapse.php' => config_path('synapse.php'),
        ], 'synapse-config');

        $this->publishes([
                             __DIR__.'/../database/migrations' => database_path('migrations'),
                         ], 'synapse-migrations');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'synapse');

    }
}
