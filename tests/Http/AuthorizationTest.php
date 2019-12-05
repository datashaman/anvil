<?php

namespace Datashaman\Anvil\Tests\Http;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Datashaman\Anvil\Anvil;
use Datashaman\Anvil\AnvilApplicationServiceProvider;
use Datashaman\Anvil\Tests\TestCase;
use Orchestra\Testbench\Http\Middleware\VerifyCsrfToken;

class AuthorizationTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return array_merge(
            parent::getPackageProviders($app),
            [AnvilApplicationServiceProvider::class]
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([VerifyCsrfToken::class]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        Anvil::auth(null);
    }

    public function test_clean_anvil_installation_denies_access_by_default()
    {
        $this->post('/anvil/anvil-api/requests')
            ->assertStatus(403);
    }

    public function test_clean_anvil_installation_denies_access_by_default_for_any_auth_user()
    {
        $this->actingAs(new Authenticated);

        $this->post('/anvil/anvil-api/requests')
            ->assertStatus(403);
    }

    public function test_guests_gets_unauthorized_by_gate()
    {
        Anvil::auth(function (Request $request) {
            return Gate::check('viewAnvil', [$request->user()]);
        });

        Gate::define('viewAnvil', function ($user) {
            return true;
        });

        $this->post('/anvil/anvil-api/requests')
            ->assertStatus(403);
    }

    public function test_authenticated_user_gets_authorized_by_gate()
    {
        $this->actingAs(new Authenticated);

        Anvil::auth(function (Request $request) {
            return Gate::check('viewAnvil', [$request->user()]);
        });

        Gate::define('viewAnvil', function (Authenticatable $user) {
            return $user->getAuthIdentifier() === 'anvil-test';
        });

        $this->post('/anvil/anvil-api/requests')
            ->assertStatus(200);
    }

    public function test_guests_can_be_authorized()
    {
        Anvil::auth(function (Request $request) {
            return Gate::check('viewAnvil', [$request->user()]);
        });

        Gate::define('viewAnvil', function (?Authenticatable $user) {
            return true;
        });

        $this->post('/anvil/anvil-api/requests')
            ->assertStatus(200);
    }

    public function test_unauthorized_requests()
    {
        Anvil::auth(function () {
            return false;
        });

        $this->get('/anvil/anvil-api/requests')
            ->assertStatus(403);
    }

    public function test_authorized_requests()
    {
        Anvil::auth(function () {
            return true;
        });

        $this->post('/anvil/anvil-api/requests')
            ->assertSuccessful();
    }
}

class Authenticated implements Authenticatable
{
    public $email;

    public function getAuthIdentifierName()
    {
        return 'Anvil Test';
    }

    public function getAuthIdentifier()
    {
        return 'anvil-test';
    }

    public function getAuthPassword()
    {
        return 'secret';
    }

    public function getRememberToken()
    {
        return 'i-am-anvil';
    }

    public function setRememberToken($value)
    {
        //
    }

    public function getRememberTokenName()
    {
        //
    }
}
