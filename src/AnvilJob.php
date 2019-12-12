<?php

namespace Datashaman\Anvil;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\BufferedOutput;

class AnvilJob extends BufferedOutput implements ShouldQueue
{
    use Queueable;

    /**
     * @var Run
     */
    protected $run;

    public function __construct(Run $run)
    {
        parent::__construct();
        $this->run = $run;
    }

    public function handle()
    {
        $this->run->started_at = Carbon::now();
        $this->run->exit_code = Artisan::call($this->run->command, $this->run->input, $this);
        $this->run->ended_at = Carbon::now();
        $this->run->output = $this->fetch();
        $this->run->save();
    }

    protected function doWrite($message, $newline)
    {
        parent::doWrite($message, $newline);

        event(new AnvilOutput($this->run, $message, $newline));
    }
}
