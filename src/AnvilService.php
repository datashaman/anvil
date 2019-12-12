<?php

namespace Datashaman\Anvil;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputDefinition;

class AnvilService
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
    protected function getFilteredCommands(): Collection
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
     * @param InputDefinition $definition
     *
     * @return array
     */
    protected function generateForm(
        InputDefinition $definition
    ): array {
        $form = collect($definition->getArguments())
            ->reduce(
                function ($form, $argument) {
                    $form[] = [
                        'type' => 'argument',
                        'name' => $argument->getName(),
                        'label' => Str::title($argument->getName()),
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
                        'label' => Str::title($option->getName()),
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
            'type' => 'command',
            'content' => [
                'command' => $name,
                'description' => $command->getDescription(),
                'help' => $command->getHelp(),
                'options' => $definition->getOptions(),
                'arguments' => $definition->getArguments(),
                'synopsis' => $definition->getSynopsis(),
                'form' => $this->generateForm($definition),
            ],
            'created_at' => null,
        ];
    }

    /**
     * @return array
     */
    public function getCommands(): array
    {
        return $this
            ->getFilteredCommands()
            ->map(
                function ($command, $name) {
                    return $this->mapCommand($command, $name);
                }
            )
            ->values()
            ->all();
    }

    /**
     * @param string $command
     *
     * @return array
     */
    public function getCommand(string $command): array
    {
        return $this
            ->getFilteredCommands()
            ->only($command)
            ->map(
                function ($command, $name) {
                    return $this->mapCommand($command, $name);
                }
            )
            ->first();
    }
}
