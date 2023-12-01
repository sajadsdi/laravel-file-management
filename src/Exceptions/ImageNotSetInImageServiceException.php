<?php

namespace Sajadsdi\LaravelFileManagement\Exceptions;


class ImageNotSetInImageServiceException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Image is not set, please set image before any action!');
    }
}
