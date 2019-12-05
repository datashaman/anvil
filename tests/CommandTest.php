<?php

namespace Datashaman\Anvil\Tests;

class CommandTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([Authorize::class, VerifyCsrfToken::class]);
    }

    public function testNamedRoute()
    {
        $this->assertEquals(
            url(config('anvil.path')),
            route('anvil')
        );
    }
}
