<?php

namespace Datashaman\Anvil;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AnvilServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (!config('anvil.enabled')) {
            return;
        }

        Route::middlewareGroup(
            'anvil',
            config('anvil.middleware', [])
        );

        $this->registerRoutes();
        $this->registerPublishing();

        $this->loadViewsFrom(
            __DIR__ . '/../resources/views',
            'anvil'
        );
    }

    private function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/Http/routes.php');
        });
    }

    private function routeConfiguration()
    {
        return [
            'domain' => config('anvil.domain', null),
            'namespace' => 'Datashaman\Anvil\Http\Controllers',
            'prefix' => config('anvil.path'),
            'middleware' => 'anvil',
        ];
    }

    private function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../public' => public_path('vendor/anvil'),
            ], 'anvil-assets');
            $this->publishes([
                __DIR__.'/../config/anvil.php' => config_path('anvil.php'),
            ], 'anvil-config');
            $this->publishes([
                __DIR__.'/../stubs/AnvilServiceProvider.stub' => app_path('Providers/AnvilServiceProvider.php'),
            ], 'anvil-provider');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__  . '/../config/anvil.php', 'anvil'
        );

        $this->commands([
            Console\InstallCommand::class,
            Console\PublishCommand::class,
        ]);
    }
}
