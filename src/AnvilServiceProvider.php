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
        $this->registerMigrations();
        $this->registerPublishing();

        $this->loadViewsFrom(
            __DIR__ . '/../resources/views',
            'anvil'
        );
    }

    /**
     * Register the package routes.
     */
    private function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/Http/routes.php');
        });
    }

    /**
     * Get the Anvil route group configuration array.
     *
     * @return array
     */
    private function routeConfiguration(): array
    {
        return [
            'domain' => config('anvil.domain', null),
            'namespace' => 'Datashaman\Anvil\Http\Controllers',
            'prefix' => config('anvil.path'),
            'middleware' => 'anvil',
        ];
    }

    /**
     * Register the package's migrations.
     *
     * @return void
     */
    private function registerMigrations()
    {
        if ($this->app->runningInConsole() && $this->shouldMigrate()) {
            $this->loadMigrationsFrom(__DIR__.'/../migrations');
        }
    }

    private function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../migrations' => database_path('migrations'),
            ], 'anvil-migrations');
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
            __DIR__  . '/../config/anvil.php',
            'anvil'
        );

        $this->commands([
            Console\InstallCommand::class,
            Console\PublishCommand::class,
        ]);
    }

    /**
     * Determine if we should register the migrations.
     *
     * @return bool
     */
    protected function shouldMigrate()
    {
        return Anvil::$runsMigrations;
    }
}
