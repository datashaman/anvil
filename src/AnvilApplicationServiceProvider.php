<?php

namespace Datashaman\Anvil;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AnvilApplicationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->authorization();
    }

    /**
     * Configure the Anvil authorization services.
     *
     * @return void
     */
    protected function authorization()
    {
        $this->gate();

        Anvil::auth(function ($request) {
            return app()->environment('local') ||
                   Gate::check('viewAnvil', [$request->user()]);
        });
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

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
