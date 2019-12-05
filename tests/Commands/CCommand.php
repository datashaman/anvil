<?php

namespace Datashaman\Anvil\Tests\Commands;

use Illuminate\Console\Command;

class CCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'app:ccommand
        {--option}
        {--optionalDefault=default}
        {--optionalValue=}
        {argument}
        {argumentDefault=default}
    ';

    public function handle()
    {
        $this->info('Option: ' . $this->option('option'));
        $this->info('Option With Default: ' . $this->option('optionalDefault'));
        $this->info('Option With Value: ' . $this->option('optionalValue'));
        $this->info('Argument: ' . $this->argument('argument'));
        $this->info('Argument with Default: ' . $this->argument('argumentDefault'));
    }
}
