<?php

namespace Datashaman\Anvil\Tests;

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

    protected function resolveApplicationCore($app)
    {
        parent::resolveApplicationCore($app);

        $app->detectEnvironment(
            function () {
                return 'self-testing';
            }
        );
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
        $config = $app->get('config');

        $config->set('logging.default', 'errorlog');

        $config->set(
            'anvil.commands',
            [
                'app:acommand',
                'app:bcommand',
            ]
        );
    }
}
