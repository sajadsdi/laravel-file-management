<?php

namespace Sajadsdi\LaravelFileManagement\Exceptions;


class FileIsTrashedException extends \Exception
{
    public function __construct(public string|int $fileId)
    {
        parent::__construct("The '{$this->fileId}' file is trashed! You can't trash a trashed files.");
    }
}
