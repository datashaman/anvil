<?php

namespace Datashaman\Anvil;

use Illuminate\Support\Facades\Facade;

class AnvilFacade extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'anvil';
    }
}
