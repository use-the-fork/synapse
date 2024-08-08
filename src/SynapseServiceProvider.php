<?php

declare(strict_types=1);

namespace UseTheFork\Synapse;

use Illuminate\Support\ServiceProvider;

class SynapseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/synapse.php' => config_path('synapse.php'),
        ]);
    }

    /**
     * Register the service provider.
     */
    public function register() {}
}
