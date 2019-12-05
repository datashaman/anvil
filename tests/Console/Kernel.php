<?php

namespace Datashaman\Anvil\Tests\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        ACommand::class,
        BCommand::class,
        CCommand::class,
    ];
}
