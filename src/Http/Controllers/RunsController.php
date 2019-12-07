<?php

namespace Datashaman\Anvil\Http\Controllers;

use Illuminate\Console\Command;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

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
