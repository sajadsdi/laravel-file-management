<?php

namespace Sajadsdi\LaravelFileManagement;

use Illuminate\Http\UploadedFile;
use Sajadsdi\LaravelFileManagement\Concerns\JobToolsTrait;
use Sajadsdi\LaravelFileManagement\Concerns\StorageToolsTrait;
use Sajadsdi\LaravelFileManagement\Concerns\UploadToolsTrait;
use Sajadsdi\LaravelFileManagement\Contracts\FileRepositoryInterface;
use Sajadsdi\LaravelFileManagement\Exceptions\FileIsInProgressException;
use Sajadsdi\LaravelFileManagement\Exceptions\FileIsNotFoundException;
use Sajadsdi\LaravelFileManagement\Exceptions\FileIsNotTrashedException;
use Sajadsdi\LaravelFileManagement\Exceptions\FileIsTrashedException;
use Sajadsdi\LaravelFileManagement\Jobs\Update\InProgressFile;

class FileManagement
{
    use StorageToolsTrait, UploadToolsTrait, JobToolsTrait;

    private array $config;
    private FileRepositoryInterface $fileRepository;

    public function __construct(array $config, FileRepositoryInterface $fileRepository)
    {
        $this->config = $config;
        $this->fileRepository = $fileRepository;
    }

    /**
     * @param UploadedFile $file
     * @param string|null $leftPath
     * @param string|null $rightPath
     * @param string|null $customFileName
     * @return array
     * @throws Exceptions\MimeTypeNotAllowedException
     * @throws Exceptions\SizeNotAllowedException
     */
    public function upload(UploadedFile $file, ?string $leftPath = null, ?string $rightPath = null, ?string $customFileName = null): array
    {
        //generate unique file name
        $fileName = $this->generateFileName($file, $customFileName);

        //get file info and validate file mime and extension
        [$fileExt, $fileType] = $this->getFileInfo($file->getMimeType(), strtolower($file->getClientOriginalExtension()));

        //validate file size
        $fileSize = $file->getSize();
        $this->validateFileSize($fileSize, $fileType);

        $secureExt = $this->getSecureFileExt($fileType, $fileExt);
        $config = $this->config['types'][$fileType];
        $fileTitle = $this->getOriginalFileName($file);
        $path = $this->generatePath($config, $leftPath, $rightPath);
        $tempPath = $this->getUploadTempPath($file->getRealPath(), $path, $fileName . '.' . $secureExt, $config);

        $insertedFile = $this->fileRepository->create([
            'type' => $fileType,
            'title' => $fileTitle,
            'name' => $fileName,
            'ext' => $fileExt,
            'path' => $path . $fileName . '_fm.' . $secureExt,
            'disk' => $config['disk'],
            'size' => $fileSize
        ]);

        if ($insertedFile) {
            $this->dispatchJob('upload', $config, $tempPath, $insertedFile);

            unset($insertedFile['disk'], $insertedFile['path']);

            return $insertedFile;
        }

        return [];
    }

    /**
     * @param string|int $fileId
     * @return void
     * @throws Exceptions\FileIsNotFoundException
     * @throws Exceptions\FileIsTrashedException
     */
    public function trash(string|int $fileId): void
    {
        $file = $this->fileRepository->getById($fileId);

        if (!$file) {
            throw new FileIsNotFoundException($fileId);
        }

        if($file->status == 1){
            throw new FileIsInProgressException($fileId);
        }

        if ($file->trashed_at) {
            throw new FileIsTrashedException($fileId);
        }

        InProgressFile::dispatchSync($file->id, $this->config['trash']['queue']);

        $this->dispatchJob('trash', $this->config['trash'], $file->makeVisible(["path", "disk"])->toArray());
    }

    /**
     * @param string|int $fileId
     * @return void
     * @throws Exceptions\FileIsNotFoundException
     * @throws Exceptions\FileIsTrashedException
     */
    public function restoreTrash(string|int $fileId): void
    {
        $file = $this->fileRepository->getById($fileId);

        if (!$file) {
            throw new FileIsNotFoundException($fileId);
        }

        if($file->status == 1){
            throw new FileIsInProgressException($fileId);
        }

        if (!$file->trashed_at) {
            throw new FileIsNotTrashedException($fileId);
        }

        $config = $this->config['types'][$file->type];
        $config['trash_start_path'] = $this->config['trash']['start_path'];

        InProgressFile::dispatchSync($file->id, $config['queue']);

        $this->dispatchJob('restore_trash', $config, $file->makeVisible(["path", "disk"])->toArray());
    }


    public function delete(string|int $fileId): void
    {
        $file = $this->fileRepository->getById($fileId);

        if (!$file) {
            throw new FileIsNotFoundException($fileId);
        }

        if($file->status == 1){
            throw new FileIsInProgressException($fileId);
        }

        if (!$file->trashed_at) {
            throw new FileIsNotTrashedException($fileId);
        }

        $config = $this->config['types'][$file->type];

        InProgressFile::dispatchSync($file->id, $config['queue']);

        $this->dispatchJob('delete', $config, $file->makeVisible(["path", "disk"])->toArray());
    }

    public function update(string|int $fileId, array $updates): void
    {
        $file = $this->fileRepository->getById($fileId);

        if (!$file) {
            throw new FileIsNotFoundException($fileId);
        }

        if($file->status == 1){
            throw new FileIsInProgressException($fileId);
        }

        $config = $this->config['types'][$file->type];

        InProgressFile::dispatchSync($file->id, $config['queue']);

        $this->dispatchJob('update', $config, $file->makeVisible(["path", "disk"])->toArray(), $updates);
    }

    public function single(string|int $fileId): array
    {
        $file = $this->fileRepository->getById($fileId);

        if (!$file) {
            throw new FileIsNotFoundException($fileId);
        }

        return $file->makeVisible(["path", "disk"])->toArray();
    }
}
