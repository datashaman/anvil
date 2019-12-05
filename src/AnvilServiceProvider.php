<?php

namespace Datashaman\Anvil;

use Illuminate\Support\ServiceProvider;

class AnvilServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(
            'anvil',
            function ($app) {
                return new AnvilService($this->app, config('anvil'));
            }
        );
    }
}
