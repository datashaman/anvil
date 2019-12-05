<?php

namespace Datashaman\Anvil\Tests;

use Datashaman\Anvil\AnvilFacade;
use Datashaman\Anvil\AnvilServiceProvider;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    /**
     * @param Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            AnvilServiceProvider::class,
        ];
    }

    /**
     * @param Application $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'Anvil' => AnvilFacade::class,
        ];
    }

    protected function resolveApplicationConsoleKernel($app)
    {
        $app->singleton(Kernel::class, Console\Kernel::class);
    }

    /**
     * @param Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('anvil', [
            'commands' => [
                Console\ACommand::class,
                Console\BCommand::class,
            ],
        ]);
    }
}
