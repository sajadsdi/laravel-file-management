<?php

namespace Sajadsdi\LaravelFileManagement\Jobs\Update;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Sajadsdi\LaravelFileManagement\Contracts\FileRepositoryInterface;

class UpdateFileDetails implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;


    /**
     * Create a new job instance.
     */
    public function __construct(public string|int $fileId, public array $details, string $queue)
    {
        $this->onQueue($queue);
    }

    /**
     * Execute the job.
     */
    public function handle(FileRepositoryInterface $fileRepository)
    {
        $file = $fileRepository->getById($this->fileId);

        if ($file) {
            $fileRepository->update($this->fileId, ['details' => array_merge((array) $file->details, $this->details)]);
        }
    }
}
