<?php

namespace Sajadsdi\LaravelFileManagement\Jobs\Trash;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Sajadsdi\LaravelFileManagement\Contracts\FileRepositoryInterface;
use Sajadsdi\LaravelFileManagement\Events\Trash\AfterTrash;
use Sajadsdi\LaravelFileManagement\Events\Trash\BeforeTrash;
use Sajadsdi\LaravelFileManagement\Jobs\MoveFileJob;
use Sajadsdi\LaravelFileManagement\Jobs\Update\VerifyFile;

class TrashJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;


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
        BeforeTrash::dispatch($this->config, $this->file);

        $newPath = $this->config['start_path'] . '/' . $this->file['path'];

        //Move file to trash
        MoveFileJob::dispatchSync($this->config, $this->file['disk'], $this->file['path'], $this->config['disk'], $newPath);

        //update new path and disk
        $updatedFile = $fileRepository->update($this->file['id'], [
            'disk' => $this->config['disk'],
            'path' => $newPath,
            'trashed_at' => Carbon::now()
        ]);

        AfterTrash::dispatch($this->config, $updatedFile);

        VerifyFile::dispatchSync($this->file['id'], $this->config['queue']);
    }
}
