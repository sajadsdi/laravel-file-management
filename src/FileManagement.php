<?php

namespace Sajadsdi\LaravelFileManagement;

use Illuminate\Http\UploadedFile;
use Sajadsdi\LaravelFileManagement\Concerns\JobToolsTrait;
use Sajadsdi\LaravelFileManagement\Concerns\StorageToolsTrait;
use Sajadsdi\LaravelFileManagement\Concerns\UploadToolsTrait;
use Sajadsdi\LaravelFileManagement\Contracts\FileRepositoryInterface;

class FileManagement
{
    use StorageToolsTrait, UploadToolsTrait, JobToolsTrait;

    private array                   $config;
    private FileRepositoryInterface $fileRepository;

    public function __construct(array $config, FileRepositoryInterface $fileRepository)
    {
        $this->config         = $config;
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
        $fileName = $this->generateFileName($file, $customFileName);
        //get file info and validate file mime and extension
        [$fileExt, $fileType] = $this->getFileInfo($file->getMimeType(), strtolower($file->getClientOriginalExtension()));
        //validate file size
        $this->validateFileSize($file->getSize(),$fileType);

        $secureExt = $this->getSecureFileExt($fileType, $fileExt);
        $config    = $this->config['types'][$fileType];
        $fileTitle = $this->getOriginalFileName($file);
        $path      = $this->generatePath($config, $leftPath, $rightPath);
        $tempPath  = $this->getUploadTempPath($file->getRealPath(), $path, $fileName . '.' . $secureExt, $config);

        $insertedFile = $this->fileRepository->createFile([
            'type'  => $config['type']->value,
            'title' => $fileTitle,
            'name'  => $fileName,
            'ext'   => $fileExt,
            'path'  => $path . $fileName . '_fm.' . $secureExt,
            'disk'  => $config['disk']
        ]);

        $this->dispatchJob('upload', $config, [$tempPath, $insertedFile]);

        return $insertedFile?->toArray() ?? [];
    }


}
