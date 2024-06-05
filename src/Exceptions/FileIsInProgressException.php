<?php

namespace Sajadsdi\LaravelFileManagement\Exceptions;


class FileIsInProgressException extends \Exception
{
    public function __construct(public string|int $fileId)
    {
        parent::__construct("The '{$this->fileId}' file is being processed. Please wait until the processing is complete.");
    }
}
