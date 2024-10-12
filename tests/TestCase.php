<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Tests;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as Orchestra;
use UseTheFork\Synapse\SynapseServiceProvider;
use function Orchestra\Testbench\workbench_path;

abstract class TestCase extends Orchestra
{
    use WithWorkbench;
    use RefreshDatabase;

    /**
     * Define database migrations.
     */
    protected function defineDatabaseMigrations(): void
    {
        // Testing migrations are located in workbench database/migrations
        $this->loadMigrationsFrom(
            __DIR__.'/../database/migrations'
        );

        $this->loadMigrationsFrom(
            workbench_path('database/migrations')
        );
    }

    protected function defineDatabaseSeeders(): void {}

    protected function defineEnvironment($app): void {}

    protected function getEnvironmentSetUp($app): void
    {

        // make sure, our .env file is loaded
        $app->useEnvironmentPath(__DIR__.'/..');
        $app->bootstrapWith([LoadEnvironmentVariables::class]);

        // Loads our config instead of manually setting it.
        $synapseConfig = require __DIR__.'/../config/synapse.php';
        tap($app['config'], function (Repository $config) use ($synapseConfig) {
            $config->set('synapse', $synapseConfig);
        });



        parent::getEnvironmentSetUp($app);
    }

    protected function getPackageProviders($app): array
    {
        return [
            SynapseServiceProvider::class,
        ];
    }
}
