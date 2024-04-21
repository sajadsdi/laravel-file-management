<?php

namespace Sajadsdi\LaravelFileManagement\Concerns;

use Illuminate\Http\UploadedFile;
use Sajadsdi\LaravelFileManagement\Exceptions\MimeTypeNotAllowedException;
use Sajadsdi\LaravelFileManagement\Exceptions\SizeNotAllowedException;

trait UploadToolsTrait
{
    use StorageToolsTrait;

    /**
     * @param UploadedFile $file
     * @return string
     */
    private function getOriginalFileName(UploadedFile $file): string
    {
        $fileName = trim(preg_replace('/\s+/', ' ', $file->getClientOriginalName()));
        $ext      = $this->getFileExtension($fileName);
        return str_replace(['.' . $ext, '.'], ["", "-"], $fileName);
    }

    /**
     * @param UploadedFile $file
     * @param string|null $customFileName
     * @return string
     */
    private function generateFileName(UploadedFile $file, ?string $customFileName = null): string
    {
        return ($customFileName ? $customFileName : substr(md5(time() . rand(1, 99) . $file->getClientOriginalName() . rand(1, 19)), 6, 20));
    }

    /**
     * @param string $fileRealPath
     * @param string $destinationPath
     * @param string $fileName
     * @param array $config
     * @return string
     */
    private function getUploadTempPath(string $fileRealPath, string $destinationPath, string $fileName, array $config): string
    {
        if ($config['process_to_queue']) {
            $path = $this->createDirectory('public', $this->config['temp_path'] . "." . $destinationPath) . $fileName;

            $this->putFile('public', $path, file_get_contents($fileRealPath));

            return $this->getFullPath('public', $path);
        } else {
            return $fileRealPath;
        }
    }

    /**
     * @param string $filePath
     * @return string
     */
    private function getFileExtension(string $filePath): string
    {
        $tmp = explode('.', $filePath);

        return $tmp[count($tmp) - 1];
    }

    /**
     * @param string $fileType
     * @param string $fileExtension
     * @return string
     */
    private function getSecureFileExt(string $fileType, string $fileExtension): string
    {
        if (in_array($fileType, $this->config['security']['secure_types'])) {
            return $this->config['security']['secure_ext'];
        }

        return $fileExtension;
    }

    /**
     * @param string $mime
     * @param string $ext
     * @return array
     * @throws MimeTypeNotAllowedException
     */
    private function getFileInfo(string $mime, string $ext): array
    {
        foreach ($this->config['types'] as $type => $typeConfig) {
            if (isset($typeConfig['allow_mimes'][$mime])) {
                if (in_array($ext, $typeConfig['allow_mimes'][$mime])) {
                    return [$ext, $type];
                }

                if (!in_array($mime, $this->config['require_check_ext_for_mimes'])) {
                    return [$typeConfig['allow_mimes'][$mime][0], $type];
                }
            }
        }

        throw new MimeTypeNotAllowedException($mime, $ext);
    }

    /**
     * @param int $size
     * @param string $type
     * @return void
     * @throws SizeNotAllowedException
     */
    private function validateFileSize(int $size, string $type)
    {
        if(($this->config['types'][$type]['max_file_size_mb']*1048576) < $size){
            throw new SizeNotAllowedException($type,$this->config['types'][$type]['max_file_size_mb']);
        }
    }

}
