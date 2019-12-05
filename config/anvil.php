<?php

use Datashaman\Anvil\Http\Middleware\Authorize;

return [

    /*
    |--------------------------------------------------------------------------
    | Anvil Domain
    |--------------------------------------------------------------------------
    |
    | This is the subdomain where Anvil will be accessible from. If the
    | setting is null, Anvil will reside under the same domain as the
    | application. Otherwise, this value will be used as the subdomain.
    |
    */

    'domain' => env('ANVIL_DOMAIN', null),

    /*
    |--------------------------------------------------------------------------
    | Anvil Path
    |--------------------------------------------------------------------------
    |
    | This is the URI path where Anvil will be accessible from. Feel free
    | to change this path to anything you like. Note that the URI will not
    | affect the paths of its internal API that aren't exposed to users.
    |
    */

    'path' => env('ANVIL_PATH', 'anvil'),

    /*
    |--------------------------------------------------------------------------
    | Anvil Master Switch
    |--------------------------------------------------------------------------
    |
    | This option may be used to disable all Anvil watchers regardless
    | of their individual configuration, which simply provides a single
    | and convenient way to enable or disable Anvil data storage.
    |
    */

    'enabled' => env('ANVIL_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Anvil Route Middleware
    |--------------------------------------------------------------------------
    |
    | These middleware will be assigned to every Anvil route, giving you
    | the chance to add your own middleware to this list or change any of
    | the existing middleware. Or, you can simply stick with this list.
    |
    */

    'middleware' => [
        'web',
        Authorize::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Commands
    |--------------------------------------------------------------------------
    |
    | The following array lists the Artisan commands that will be managed
    | by Anvil.
    |
    */

    'commands' => [
        //
    ],
];
