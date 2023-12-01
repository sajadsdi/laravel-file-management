<?php

namespace Sajadsdi\LaravelFileManagement\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Sajadsdi\LaravelFileManagement\Model\File;

interface FileRepositoryInterface
{
    public function createFile(array $data): File;

    public function updateFile(string $fileId, array $data): Collection;

    public function deleteFile(string $fileId): void;
}
