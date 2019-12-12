<?php

namespace Datashaman\Anvil;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class AnvilOutput implements ShouldBroadcastNow
{
    /**
     * @var Run
     */
    private $run;

    /**
     * @var string
     */
    public $message;

    /**
     * @var bool
     */
    public $newline;

    public function __construct(Run $run, string $message, bool $newline)
    {
        $this->run = $run;
        $this->message = $message;
        $this->newline = $newline;
    }

    /**
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('command.'.$this->run->command);
    }
}
