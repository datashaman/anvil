<?php

namespace Datashaman\Anvil\Http\Controllers;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Input\InputDefinition;

class RunsController extends Controller
{
    /**
     * @param string $command
     *
     * @return JsonResponse
     */
    public function index(string $command): JsonResponse
    {
    }

    /**
     * @param Request $request
     * @param string $command
     *
     * @return JsonResponse
     */
    public function store(Request $request, string $command): JsonResponse
    {
    }

    /**
     * @param string $command
     * @param string $id
     *
     * @return JsonResponse
     */
    public function show(string $command, string $id): JsonResponse
    {
    }
}
