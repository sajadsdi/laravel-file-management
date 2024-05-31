<?php

namespace Sajadsdi\LaravelFileManagement\Jobs\Trash;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Sajadsdi\LaravelFileManagement\Concerns\StorageToolsTrait;
use Sajadsdi\LaravelFileManagement\Contracts\FileRepositoryInterface;
use Sajadsdi\LaravelFileManagement\Events\Trash\AfterTrash;
use Sajadsdi\LaravelFileManagement\Events\Trash\BeforeTrash;
use Sajadsdi\LaravelFileManagement\Jobs\InProgressFile;
use Sajadsdi\LaravelFileManagement\Jobs\VerifyFile;

class TrashJob implements ShouldQueue
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
        if ($this->file['trashed_at']) {
            return;
        }

        InProgressFile::dispatchSync($this->file['id'], $this->config['queue']);

        BeforeTrash::dispatch($this->config, $this->file);

        //copy and delete file
        $this->putFile($this->config['disk'], $this->config['start_path'] . '/' . $this->file['path'], $this->getFile($this->file['disk'], $this->file['path']));
        $this->deleteFile($this->file['disk'], $this->file['path']);

        //update new path and disk
        $updatedFile = $fileRepository->update($this->file['id'], [
            'disk' => $this->config['disk'],
            'path' => $this->config['start_path'] . '/' . $this->file['path'],
            'trashed_at' => Carbon::now()
        ]);

        AfterTrash::dispatch($this->config, $updatedFile);

        VerifyFile::dispatchSync($this->file['id'], $this->config['queue']);
    }
}
