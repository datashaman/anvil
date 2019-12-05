<?php

namespace Datashaman\Anvil\Tests;

use Datashaman\Anvil\AnvilFacade as Anvil;

class CommandTest extends TestCase
{
    public function testShit()
    {
        $this->artisan('app:acommand');
    }
}
