<?php

namespace Datashaman\Anvil\Http\Controllers;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Routing\Controller;

class CommandsController extends Controller
{
    /**
     * @var Kernel
     */
    protected $kernel;

    /**
     * @param Kernel $kernel
     */
    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

    public function index()
    {
        $commands = collect($this->kernel->all())
                                 ->only(config('anvil.commands'));

        return response()->json($commands);
    }
}
