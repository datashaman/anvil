<?php

namespace Datashaman\Anvil\Http\Controllers;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Routing\Controller;

class CommandsController extends Controller
{
    /**
     * @var Kernel
     */
    protected $kernel;

    /**
     * @param Kernel $kernel
     */
    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @return Collection
     */
    protected function getCommands()
    {
        return collect($this->kernel->all())
            ->only(config('anvil.commands'));
    }

    protected function mapCommand($command, $name)
    {
        $definition = $command->getDefinition();

        return [
            'id' => $name,
            'batch_id' => null,
            'type' => 'command',
            'content' => [
                'command' => $name,
                'description' => $command->getDescription(),
                'options' => $definition->getOptions(),
                'arguments' => $definition->getArguments(),
            ],
            'created_at' => null,
        ];
    }

    public function index()
    {
        $sequence = 0;

        $commands = $this
            ->getCommands()
            ->map(
                function ($command, $name) use (&$sequence) {
                    $entry = $this->mapCommand($command, $name);

                    $entry['sequence'] = $sequence++;

                    return $entry;
                }
            )
            ->values()
            ->all();

        return response()->json(
            [
                'entries' => $commands,
            ]
        );
    }

    /**
     * @param string $command
     */
    public function show(string $command)
    {
        $command = $this
            ->getCommands()
            ->only($command)
            ->map(
                function ($command, $name) {
                    return $this->mapCommand($command, $name);
                }
            )
            ->first();

        return response()->json(
            [
                'entry' => $command,
                'batch' => [$command],
            ]
        );
    }
}
