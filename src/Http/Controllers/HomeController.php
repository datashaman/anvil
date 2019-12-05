<?php

namespace Datashaman\Anvil\Http\Controllers;

use Illuminate\Routing\Controller;
use Datashaman\Anvil\Anvil;

class HomeController extends Controller
{
    /**
     * Display the Anvil view.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('anvil::layout', [
            'cssFile' => Anvil::$useDarkTheme ? 'app-dark.css' : 'app.css',
            'anvilScriptVariables' => Anvil::scriptVariables(),
            'assetsAreCurrent' => Anvil::assetsAreCurrent(),
        ]);
    }
}
