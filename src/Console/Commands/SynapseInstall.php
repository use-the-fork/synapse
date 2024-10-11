<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Console\Commands;

use Illuminate\Console\Command;

class SynapseInstall extends Command
{
    /**
     * @var string
     */
    public $description = 'Install Laravel Synapse';

    /**
     * @var string
     */
    public $signature = 'synapse:install';

    /**
     * Install Synapse
     */
    public function handle(): int
    {
        $this->info(' 🚀 | Installing Synapse');

        $this->info(' 🔭 | Publishing config...');
        $this->callSilently('vendor:publish', ['--tag' => 'synapse-config']);

        if ($this->confirm('Publish Migrations? (Used for database memory)')) {
            $this->info(' 🪐 | Publishing migrations...');
            $this->callSilently('vendor:publish', ['--tag' => 'synapse-migrations']);

            $runMigrations = $this->confirm('Would you like to run migrations?', false);

            if ($runMigrations) {
                $this->callSilently('migrate');
                $this->info(' 🎯 | Migrations run successfully');
            }

        }
        $this->info(' 💚 | Synapse has been installed.️');

        return self::SUCCESS;
    }
}
