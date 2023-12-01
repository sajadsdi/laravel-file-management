<?php

namespace Sajadsdi\LaravelFileManagement\Exceptions;


class SizeNotAllowedException extends \Exception
{
    public function __construct(public string $type, public int $allowedSize)
    {
        parent::__construct("max size allowed for '{$this->type}' type is {$this->allowedSize}MB , your file size is bigger than this limit!");
    }
}
