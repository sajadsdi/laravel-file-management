<?php

namespace Sajadsdi\LaravelFileManagement\Concerns;

use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Psr\Http\Message\StreamInterface;

trait StorageToolsTrait
{
    /**
     * @param array $config
     * @param string|null $leftPath
     * @param string|null $rightPath
     * @return string
     */
    public function generatePath(array $config, ?string $leftPath = null, ?string $rightPath = null): string
    {
        $date = $config['create_date_paths'] ? date("Y.m" . ($config['date_paths_with_day'] ? ".d" : "")) : "";

        $path = ($leftPath ? $leftPath . "." : "") . $config['start_path'] . ($date ? "." . $date : "") . ($rightPath ? "." . $rightPath : "");

        return $this->createDirectory($config['disk'], $path);
    }

    /**
     * @param string $disk
     * @param string $directories
     * @return string
     */
    public function createDirectory(string $disk, string $directories): string
    {
        $path = $this->normalizePath($directories);

        if (!$this->existDirectory($disk, $path)) {
            Storage::disk($disk)->makeDirectory($path);
        }

        return $path . '/';
    }

    /**
     * @param string $disk
     * @param string $directories
     * @return void
     */
    public function removeDirectory(string $disk, string $directories): void
    {
        $path = $this->normalizePath($directories);

        if ($this->existDirectory($disk, $path)) {
            Storage::disk($disk)->deleteDirectory($path);
        }
    }

    /**
     * @param string $disk
     * @param string $path
     * @return string
     */
    public function getFullPath(string $disk, string $path): string
    {
        return $this->normalizePath(Storage::disk($disk)->path($path));
    }

    public function normalizePath(string $path): string
    {
        return preg_replace('/\/{2,}/', '/', str_replace(['.', '\\'], '/', $path));
    }

    /**
     * @param string $disk
     * @param string $path
     * @return bool
     */
    public function existDirectory(string $disk, string $path): bool
    {
        $aPath = explode('/', $path);
        $count = count($aPath);
        $path  = str_replace('/' . $aPath[$count - 1], '', $path);

        return in_array($aPath[$count - 1], Storage::disk($disk)->directories($path));
    }

    /**
     * @param string $disk
     * @param string $path
     * @param StreamInterface|File|UploadedFile|string|resource $contents
     * @param mixed $options
     * @return bool
     */
    public function putFile(string $disk, string $path, mixed $contents, mixed $options = 'public'): bool
    {
        return Storage::disk($disk)->put($path, $contents, $options);
    }

    /**
     * @param string $disk
     * @param string $filePath
     * @return void
     */
    public function deleteFile(string $disk, string $filePath)
    {
        if (Storage::disk($disk)->exists($filePath)) {
            Storage::disk($disk)->delete($filePath);
        }
    }
}
