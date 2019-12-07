<?php

namespace Datashaman\Anvil;

use Illuminate\Support\Facades\File;

class Anvil
{
    use AuthorizesRequests;

    /**
     * Indicates if Anvil should use the dark theme.
     *
     * @var bool
     */
    public static $useDarkTheme = false;

    /**
     * Indicates if Anvil migrations will be run.
     *
     * @var bool
     */
    public static $runsMigrations = true;

    /**
     * Specifies that Anvil should use the dark theme.
     *
     * @return static
     */
    public static function night()
    {
        static::$useDarkTheme = true;

        return new static;
    }

    /**
     * Get the default JavaScript variables for Anvil.
     *
     * @return array
     */
    public static function scriptVariables()
    {
        return [
            'path' => config('anvil.path'),
            'timezone' => config('app.timezone'),
            'routes' => [
                'runs_store' => route('runs.store', ['command' => '%command%']),
            ],
        ];
    }

    /**
     * Configure Anvil to not register its migrations.
     *
     * @return static
     */
    public static function ignoreMigrations()
    {
        static::$runsMigrations = false;

        return new static;
    }

    /**
     * Check if assets are up-to-date.
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     * @return bool
     */
    public static function assetsAreCurrent()
    {
        $publishedPath = public_path('vendor/anvil/mix-manifest.json');

        if (! File::exists($publishedPath)) {
            throw new \RuntimeException('The Anvil assets are not published. Please run: php artisan anvil:publish');
        }

        return File::get($publishedPath) === File::get(__DIR__.'/../public/mix-manifest.json');
    }
}
