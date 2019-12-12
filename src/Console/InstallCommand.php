<?php

namespace Datashaman\Anvil\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'anvil:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the Anvil resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment('Publishing Anvil Service Provider...');
        $this->callSilent('vendor:publish', ['--tag' => 'anvil-provider']);

        $this->comment('Publishing Anvil Assets...');
        $this->callSilent('vendor:publish', ['--tag' => 'anvil-assets']);

        $this->comment('Publishing Anvil Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'anvil-config']);

        $this->registerAnvilServiceProvider();

        $this->info('Anvil scaffolding installed successfully.');
    }

    /**
     * Register the Anvil service provider in the application configuration file.
     *
     * @return void
     */
    protected function registerAnvilServiceProvider()
    {
        $namespace = Str::replaceLast('\\', '', $this->getAppNamespace());

        $appConfig = file_get_contents(config_path('app.php'));

        if (Str::contains($appConfig, $namespace.'\\Providers\\AnvilServiceProvider::class')) {
            return;
        }

        $lineEndingCount = [
            "\r\n" => substr_count($appConfig, "\r\n"),
            "\r" => substr_count($appConfig, "\r"),
            "\n" => substr_count($appConfig, "\n"),
        ];

        $eol = array_keys($lineEndingCount, max($lineEndingCount))[0];

        file_put_contents(config_path('app.php'), str_replace(
            "{$namespace}\\Providers\EventServiceProvider::class,".$eol,
            "{$namespace}\\Providers\EventServiceProvider::class,".$eol."        {$namespace}\Providers\AnvilServiceProvider::class,".$eol,
            $appConfig
        ));

        file_put_contents(app_path('Providers/AnvilServiceProvider.php'), str_replace(
            "namespace App\Providers;",
            "namespace {$namespace}\Providers;",
            file_get_contents(app_path('Providers/AnvilServiceProvider.php'))
        ));
    }
}
