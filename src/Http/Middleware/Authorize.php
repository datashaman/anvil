<?php

namespace Datashaman\Anvil\Http\Middleware;

use Datashaman\Anvil\Anvil;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        return Anvil::check($request) ? $next($request) : abort(403);
    }
}
