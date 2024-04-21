<?php

namespace Sajadsdi\LaravelFileManagement\Jobs\Upload;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;

class DeleteUploadTempJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;


    /**
     * Create a new job instance.
     */
    public function __construct(public array $config, public string $tempPath)
    {
        $this->onQueue($config['queue']);
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        unlink($this->tempPath);
    }

}
