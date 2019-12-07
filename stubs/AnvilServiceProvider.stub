<?php

namespace App\Providers;

use Datashaman\Anvil\Anvil;
use Illuminate\Support\Facades\Gate;
use Datashaman\Anvil\AnvilApplicationServiceProvider;

class AnvilServiceProvider extends AnvilApplicationServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Anvil::night();
    }

    /**
     * Register the Anvil gate.
     *
     * This gate determines who can access Anvil in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewAnvil', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }
}
