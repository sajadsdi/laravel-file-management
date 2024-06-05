<?php

namespace Sajadsdi\LaravelFileManagement\Jobs\Delete;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Sajadsdi\LaravelFileManagement\Concerns\StorageToolsTrait;
use Sajadsdi\LaravelFileManagement\Contracts\FileRepositoryInterface;
use Sajadsdi\LaravelFileManagement\Events\Delete\AfterDelete;
use Sajadsdi\LaravelFileManagement\Events\Delete\BeforeDelete;
use Sajadsdi\LaravelFileManagement\Jobs\Update\InProgressFile;
use Sajadsdi\LaravelFileManagement\Jobs\Update\VerifyFile;

class DeleteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, StorageToolsTrait;


    /**
     * Create a new job instance.
     */
    public function __construct(public array $config, public array $file)
    {
        $this->onQueue($this->config['queue']);
    }

    /**
     * Execute the job.
     */
    public function handle(FileRepositoryInterface $fileRepository)
    {
        BeforeDelete::dispatch($this->config, $this->file);

        // delete file
        $this->deleteFile($this->file['disk'], $this->file['path']);

        AfterDelete::dispatch($this->config, $this->file);

        VerifyFile::dispatchSync($this->file['id'], $this->config['queue']);

        // soft delete
        $fileRepository->delete($this->file['id']);
    }
}
