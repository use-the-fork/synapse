<?php

declare(strict_types=1);

namespace UseTheFork\Synapse\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Artisan;
use UseTheFork\Synapse\Agents\SynapseArtisanAgent;
use function Laravel\Prompts\select;
use function Laravel\Prompts\spin;
use function Laravel\Prompts\text;

class SynapseArtisan extends Command
{
    /**
     * @inheritdoc
     */
    public $description = 'Ask Synapse about an Artisan command';

    /**
     * @inheritdoc
     */
    public $signature = 'synapse:ask';

    /**
     * Run the command
     */
    public function handle(): int
    {
        $command = text('What would you like artisan to do?');
        return $this->executeAgent($command);
    }

    /**
     * Executes a task using the SynapseArtisanAgent and provides options to the user to run, edit, revise, or cancel the command.
     *
     * @param string $task The input task for the SynapseArtisanAgent.
     *
     * @return int The status code indicating success or failure.
     */
    private function executeAgent(string $task): int
    {
        $synapseArtisanAgent = new SynapseArtisanAgent;

        while (true) {
            $result = spin(
                callback: fn() => $synapseArtisanAgent->handle(['input'   => $task,
                                                                'version' => Application::VERSION
                                                               ]),
                message : 'Loading...'
            );
            $result = $result->content();
            $command = $result['command'];

            $choice = select(
                label  : $command,
                options: [
                             'yes'    => '✅ Yes (Run command)',
                             'edit'   => '✏ Edit (Make changes to command before running)',
                             'revise' => '🔁 Revise (Give Feedback for a new result)',
                             'cancel' => '🛑 Cancel (Exit without running command)',
                         ]
            );

           switch ($choice) {
               case 'yes':
                    Artisan::call($command);
                    return self::SUCCESS;
               case 'edit':
                   $command = text(
                       label: 'You can edit command here:',
                       default: $command,
                   );
                   Artisan::call($command);
                    return self::SUCCESS;
               case 'revise':
                   $task = text(
                       label: 'Response to Agent:',
                       required: true
                   );
                    break;
               case 'cancel':
                    return self::FAILURE;
           }

        }
    }
}
