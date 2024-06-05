<?php

namespace Sajadsdi\LaravelFileManagement\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Sajadsdi\LaravelFileManagement\Concerns\StorageToolsTrait;

class MoveFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, StorageToolsTrait;


    /**
     * Create a new job instance.
     */
    public function __construct(public array $config, public string $oldDisk, public string $oldPath, public string $newDisk, public string $newPath)
    {
        $this->onQueue($this->config['queue']);
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        //Copy file to new path and disk
        $this->putFile($this->newDisk, $this->newPath, $this->getFile($this->oldDisk, $this->oldPath));

        //Delete old file
        $this->deleteFile($this->oldDisk, $this->oldPath);
    }


}
