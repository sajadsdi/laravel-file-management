<?php

namespace Sajadsdi\LaravelFileManagement\Exceptions;


class MimeTypeNotAllowedException extends \Exception
{
    public function __construct(public string $mime, public string $extension)
    {
        parent::__construct("The '{$this->mime}' mime type with '{$this->extension}' extension is not allowed!");
    }
}
