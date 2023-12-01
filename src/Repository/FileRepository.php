<?php

namespace Sajadsdi\LaravelFileManagement\Repository;

use Illuminate\Database\Eloquent\Collection;
use Sajadsdi\LaravelFileManagement\Contracts\FileRepositoryInterface;
use Sajadsdi\LaravelFileManagement\Model\File;

class FileRepository implements FileRepositoryInterface
{
    private File $file;

    /**
     * @param File $file
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }


    /**
     * @param array $data
     * @return File
     */
    public function createFile(array $data): File
    {
        return $this->file->create($data);
    }

    /**
     * @param string $fileId
     * @param array $data
     * @return Collection
     */
    public function updateFile(string $fileId, array $data): Collection
    {
        return $this->file->find($fileId)->update($data);
    }

    /**
     * @param string $fileId
     * @return void
     */
    public function deleteFile(string $fileId): void
    {
        $this->file->find($fileId)->delete();
    }
}
