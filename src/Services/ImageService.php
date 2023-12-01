<?php

namespace Sajadsdi\LaravelFileManagement\Services;

use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Sajadsdi\LaravelFileManagement\Exceptions\ImageNotSetInImageServiceException;

class ImageService
{
    private ImageManager $manager;
    private Image        $image;

    public function __construct(string $driver = 'gd')
    {
        $this->manager = new ImageManager(['driver' => $driver]);
    }

    public function setImage(string $path): static
    {
        $this->image = $this->manager->make($path);
        return $this;
    }

    /**
     * @return void
     */
    public function unsetImage(): void
    {
        unset($this->image);
    }

    /**
     * @return Image
     * @throws ImageNotSetInImageServiceException
     */
    public function getImage(): Image
    {
        $this->checkSetImage();
        return $this->image;
    }

    /**
     * @param bool $exception
     * @return bool
     * @throws ImageNotSetInImageServiceException
     */
    public function checkSetImage(bool $exception = true): bool
    {
        if (isset($this->image)) {
            return true;
        }
        if ($exception) {
            throw new ImageNotSetInImageServiceException();
        }
        return false;
    }

    /**
     * @return ImageService
     * @throws ImageNotSetInImageServiceException
     */
    public function fixExifOrientation(): static
    {
        $this->checkSetImage();
        $exif = $this->image->exif();

        if (!empty($exif['Orientation'])) {
            switch ($exif['Orientation']) {
                case 8:
                    $this->image->rotate(90, 0);
                    break;
                case 3:
                    $this->image->rotate(180, 0);
                    break;
                case 6:
                    $this->image->rotate(-90, 0);
                    break;
            }
        }
        return $this;
    }

    /**
     * @param $height
     * @param null $width
     * @return ImageService
     * @throws ImageNotSetInImageServiceException
     */
    public function resize($height, $width = null): static
    {
        $this->checkSetImage();
        $this->image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });
        return $this;
    }

    /**
     * @param $WatermarkPath
     * @param string $position
     * @param int $x
     * @param int $y
     * @return $this
     * @throws ImageNotSetInImageServiceException
     */
    public function addWatermark($WatermarkPath, string $position = 'bottom-left', int $x = 10, int $y = 10): static
    {
        $this->checkSetImage();
        $this->image->insert($WatermarkPath, $position, $x, $y);
        return $this;
    }

    /**
     * @param string|null $format
     * @param int $quality
     * @return Image
     * @throws ImageNotSetInImageServiceException
     */
    public function encode(string $format = null, int $quality = 100): Image
    {
        $this->checkSetImage();
        return $this->image->encode($format, $quality);
    }

    /**
     * @param string $path
     * @param int $quality
     * @param string|null $format
     * @return void
     * @throws ImageNotSetInImageServiceException
     */
    public function save(string $path, int $quality = 100, string $format = null): void
    {
        $this->checkSetImage();
        $this->image->save($path, $quality, $format);
    }


}
