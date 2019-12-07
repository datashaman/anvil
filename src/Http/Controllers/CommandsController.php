<?php

namespace Datashaman\Anvil\Http\Controllers;

use Datashaman\Anvil\AnvilService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class CommandsController extends Controller
{
    /**
     * @var AnvilService
     */
    protected $service;

    /**
     * @param AnvilService $service
     */
    public function __construct(AnvilService $service)
    {
        $this->service = $service;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $commands = $this->service->getCommands();

        return response()->json(
            [
                'entries' => $commands,
            ]
        );
    }

    /**
     * @param string $command
     *
     * @return JsonResponse
     */
    public function show(string $command): JsonResponse
    {
        $command = $this->service->getCommand($command);

        return response()->json(
            [
                'entry' => $command,
            ]
        );
    }
}
