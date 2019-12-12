<?php

namespace Datashaman\Anvil\Http\Controllers;

use Datashaman\Anvil\AnvilJob;
use Datashaman\Anvil\AnvilService;
use Datashaman\Anvil\Run;
use Illuminate\Console\Command;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class RunsController extends Controller
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
        $command = $this->service->getCommand($command);

        $input = collect(Arr::get($command, 'content.form'))
            ->reduce(
                function ($input, $field) use ($request) {
                    if ($request->has($field['name'])) {
                        $key = $field['name'];

                        if ($field['type'] === 'option') {
                            $key = "--$key";
                        }

                        $input[$key] = $request->get($field['name']);
                    }

                    return $input;
                },
                []
            );

        $run = Run::create(
            [
                'uuid' => Str::orderedUuid(),
                'command' => $command['id'],
                'input' => $input,
            ]
        );

        if (!$run->exists) {
            return new JsonResponse(
                [
                    'error' => 'Cannot store new run',
                    'status' => 'error',
                ]
            );
        }

        dispatch(new AnvilJob($run));

        return new JsonResponse(
            [
                'entry' => $run,
            ]
        );
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
