<?php

namespace Sajadsdi\LaravelFileManagement\Jobs\Upload;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Sajadsdi\LaravelFileManagement\Concerns\StorageToolsTrait;
use Sajadsdi\LaravelFileManagement\Events\AfterUpload;
use Sajadsdi\LaravelFileManagement\Events\BeforeUpload;

class UploadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, StorageToolsTrait;


    /**
     * Create a new job instance.
     */
    public function __construct(public array $config, public string $tempPath, public array $file)
    {
        $this->onQueue($this->config['queue']);
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        BeforeUpload::dispatch($this->config, $this->tempPath, $this->file);

        $this->putFile($this->file['disk'], $this->file['path'], file_get_contents($this->tempPath));

        AfterUpload::dispatch($this->config, $this->tempPath, $this->file);

        DeleteUploadTempJob::dispatchSync($this->config, $this->tempPath);
    }

}
