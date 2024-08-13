<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Tests;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables;
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
        $this->loadMigrationsFrom(
            __DIR__.'/../database/migrations'
        );
    }

    protected function defineEnvironment($app): void {}

    protected function defineDatabaseSeeders(): void {}

    protected function getEnvironmentSetUp($app): void
    {
        // make sure, our .env file is loaded
        $app->useEnvironmentPath(__DIR__.'/..');
        $app->bootstrapWith([LoadEnvironmentVariables::class]);

        tap($app['config'], function (Repository $config) {
            $config->set('synapse', [
                'openapi_key' => env('OPENAI_API_KEY'),
                'model' => env('OPENAI_API_MODEL', 'gpt-4-turbo'),
            ]);
        });

        parent::getEnvironmentSetUp($app);
    }
}
