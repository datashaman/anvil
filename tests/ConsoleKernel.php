<?php

namespace Datashaman\Anvil\Tests;

use Illuminate\Foundation\Console\Kernel;

class ConsoleKernel extends Kernel
{
    protected $commands = [
        Commands\ACommand::class,
        Commands\BCommand::class,
        Commands\CCommand::class,
    ];
}
