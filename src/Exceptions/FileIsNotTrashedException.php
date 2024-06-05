<?php

namespace Sajadsdi\LaravelFileManagement\Exceptions;


class FileIsNotTrashedException extends \Exception
{
    public function __construct(public string|int $fileId)
    {
        parent::__construct("The '{$this->fileId}' file is not trashed! This action needed trashed file.");
    }
}
