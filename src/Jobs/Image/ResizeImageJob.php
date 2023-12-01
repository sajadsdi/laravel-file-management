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

class ResizeImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, StorageToolsTrait;

    private array $heights;

    /**
     * Create a new job instance.
     */
    public function __construct(public string $tempImagePath, public File $file, public array $config, array $customHeights = [])
    {
        $this->onQueue($this->config['queue']);
        $this->setHeights($customHeights);
    }

    /**
     * Execute the job.
     * @throws ImageNotSetInImageServiceException
     */
    public function handle(ImageService $service)
    {
        if (!$service->checkSetImage(false)) {
            $service->setImage($this->tempImagePath);
        }

        foreach ($this->heights as $height) {
            $imageHeight = $service->getImage()->height();

            if ($imageHeight > $height) {
                $this->putFile($this->config['disk'], str_replace('_fm', '_' . $height, $this->file->path), $service->resize($height)->encode($this->file->ext, $this->config['quality']));
            } elseif ($this->config['duplicate_on_resize']) {
                $this->putFile($this->config['disk'], str_replace('_fm', '_' . $height, $this->file->path), $service->encode($this->file->ext, $this->config['quality']));
            }
        }

        $service->unsetImage();
        //delete temp file
        unlink($this->tempImagePath);
    }

    /**
     * @param array $customHeights
     * @return void
     */
    private function setHeights(array $customHeights): void
    {
        $this->heights = $customHeights ? $customHeights : $this->config['resize_heights'];

        rsort($this->heights);
    }

}
