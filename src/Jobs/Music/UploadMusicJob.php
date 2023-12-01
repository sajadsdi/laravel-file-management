<?php

namespace Sajadsdi\LaravelFileManagement\Jobs\Music;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Sajadsdi\LaravelFileManagement\Concerns\StorageToolsTrait;
use Sajadsdi\LaravelFileManagement\Model\File;

class UploadMusicJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, StorageToolsTrait;


    /**
     * Create a new job instance.
     */
    public function __construct(public array $config, public string $tempPath, public File $file)
    {
        $this->onQueue($config['queue']);
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        //put file from temp to storage
        $this->putFile($this->file->disk, $this->file->path, file_get_contents($this->tempPath));

        //delete file from temp
        unlink($this->tempPath);
    }

}
