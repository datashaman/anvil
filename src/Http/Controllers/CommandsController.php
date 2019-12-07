<?php

namespace Datashaman\Anvil\Http\Controllers;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputDefinition;

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
    protected function getCommands(): Collection
    {
        return collect($this->kernel->all())
            // ->only(config('anvil.commands'))
            ->filter(
                function ($command) {
                    return $command instanceof Command;
                }
            )
            ->reject(
                function ($command) {
                    return $command->isHidden();
                }
            )
            ->sortBy(
                function ($command) {
                    return $command->getName();
                }
            );
    }

    /**
     * @param Command $command
     *
     * @return array
     */
    protected function generateForm(
        Command $command,
        string $name,
        InputDefinition $definition
    ): array {
        $form = collect($definition->getArguments())
            ->reduce(
                function ($form, $argument) {
                    $form[] = [
                        'type' => 'argument',
                        'name' => $argument->getName(),
                        'required' => $argument->isRequired(),
                        'array' => $argument->isArray(),
                        'default' => $argument->getDefault(),
                        'description' => $argument->getDescription(),
                    ];

                    return $form;
                },
                []
            );

        $form = collect($definition->getOptions())
            ->reduce(
                function ($form, $option) {
                    $form[] = [
                        'type' => 'option',
                        'name' => $option->getName(),
                        'acceptValue' => $option->acceptValue(),
                        'required' => $option->isValueRequired(),
                        'optional' => $option->isValueOptional(),
                        'array' => $option->isArray(),
                        'default' => $option->getDefault(),
                        'description' => $option->getDescription(),
                    ];

                    return $form;
                },
                $form
            );

        return $form;
    }

    /**
     * @param Command $command
     * @param string $name
     *
     * @return array
     */
    protected function mapCommand(
        Command $command,
        string $name
    ): array {
        $definition = $command->getDefinition();

        return [
            'id' => $name,
            'batch_id' => null,
            'type' => 'command',
            'content' => [
                'command' => $name,
                'description' => $command->getDescription(),
                'help' => $command->getHelp(),
                'options' => $definition->getOptions(),
                'arguments' => $definition->getArguments(),
                'synopsis' => $definition->getSynopsis(),
                'form' => $this->generateForm($command, $name, $definition),
            ],
            'created_at' => null,
        ];
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
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
     *
     * @return JsonResponse
     */
    public function show(string $command): JsonResponse
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
