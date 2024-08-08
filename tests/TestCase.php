<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Tests;

use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as Orchestra;
use UseTheFork\Synapse\SynapseServiceProvider;

abstract class TestCase extends Orchestra
{
    use WithWorkbench;

    protected function getPackageProviders($app): array
    {
        return [
            SynapseServiceProvider::class,
        ];
    }

    /**
     * Define database migrations.
     */
    protected function defineDatabaseMigrations(): void
    {
        // Testing migrations are located in workbench database/migrations
        //    $this->loadMigrationsFrom(
        //      workbench_path('database/migrations')
        //    );
    }

    protected function defineDatabaseSeeders(): void {}

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testing');
    }
}
