<?php

namespace Sajadsdi\LaravelFileManagement\Jobs\Program;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class TrashProgramJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;


    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->onQueue($queue);
    }

    /**
     * Execute the job.
     */
    public function handle()
    {

    }

}
