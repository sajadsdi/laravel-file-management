<?php

namespace Sajadsdi\LaravelFileManagement\Jobs\Image;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Sajadsdi\LaravelFileManagement\Concerns\StorageToolsTrait;
use Sajadsdi\LaravelFileManagement\Exceptions\ImageNotSetInImageServiceException;
use Sajadsdi\LaravelFileManagement\Model\File;
use Sajadsdi\LaravelFileManagement\Services\ImageService;

class UploadImageJob implements ShouldQueue
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
     * @throws ImageNotSetInImageServiceException
     */
    public function handle(ImageService $service)
    {
        if ($this->config['save_original_image']) {
            $this->putFile($this->file->disk, str_replace('_fm', "_" . $this->config['original_image_suffix'], $this->file->path), file_get_contents($this->tempPath));
        }
        //put fm image
        $this->putFile($this->file->disk, $this->file->path, $service->setImage($this->tempPath)->fixExifOrientation()->encode($this->file->ext,$this->config['quality']));

        //resize all
        if($this->config['create_resize_images']) {
            ResizeImageJob::dispatchSync($this->tempPath, $this->file, $this->config);
        }else{
            $service->unsetImage();
            //delete temp file
            unlink($this->tempPath);
        }
    }

}
