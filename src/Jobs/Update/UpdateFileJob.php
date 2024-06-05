<?php

namespace Sajadsdi\LaravelFileManagement\Jobs\Update;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Sajadsdi\LaravelFileManagement\Concerns\StorageToolsTrait;
use Sajadsdi\LaravelFileManagement\Contracts\FileRepositoryInterface;
use Sajadsdi\LaravelFileManagement\Events\AfterMoveFile;
use Sajadsdi\LaravelFileManagement\Events\BeforeMoveFile;
use Sajadsdi\LaravelFileManagement\Events\Update\AfterUpdate;
use Sajadsdi\LaravelFileManagement\Events\Update\BeforeUpdate;
use Sajadsdi\LaravelFileManagement\Jobs\MoveFileJob;
use Sajadsdi\LaravelFileManagement\Jobs\Update\VerifyFile;

class UpdateFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, StorageToolsTrait;


    /**
     * Create a new job instance.
     */
    public function __construct(public array $config, public array $file, public array $updates)
    {
        $this->onQueue($this->config['queue']);
    }

    /**
     * Execute the job.
     */
    public function handle(FileRepositoryInterface $fileRepository)
    {
        if ($updates = $this->getUpdates()) {

            BeforeUpdate::dispatch($this->config, $this->file, $this->updates);

            $updatedFile = $this->processUpdate($fileRepository, $updates);

            AfterUpdate::dispatch($this->config, $updatedFile, $this->updates);

        }

        VerifyFile::dispatchSync($this->file['id'], $this->config['queue']);
    }


    private function getUpdates()
    {
        $updates = [];

        if (isset($this->updates['title']) && $this->updates['title'] && $this->updates['title'] != $this->file['title']) {
            $updates['title'] = $this->updates['title'];
        }

        if (isset($this->updates['disk']) && $this->updates['disk'] && $this->updates['disk'] != $this->file['disk']) {
            $updates['disk'] = $this->updates['disk'];
        }

        return $updates;
    }

    private function processUpdate(FileRepositoryInterface $fileRepository, array $updates): array
    {
        if (isset($updates['disk'])) {
            BeforeMoveFile::dispatch($this->config, $this->file, $updates['disk'], $this->file['path']);

            MoveFileJob::dispatchSync($this->config, $this->file['disk'], $this->file['path'], $updates['disk'], $this->file['path']);

            AfterMoveFile::dispatch($this->config, $this->file, $updates['disk'], $this->file['path']);
        }

        return $fileRepository->update($this->file['id'], $updates);
    }
}
