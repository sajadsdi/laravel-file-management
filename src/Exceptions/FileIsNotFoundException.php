<?php

namespace Sajadsdi\LaravelFileManagement\Exceptions;


class FileIsNotFoundException extends \Exception
{
    public function __construct(public string|int $fileId)
    {
        parent::__construct("The '{$this->fileId}' file is not found!");
    }
}
