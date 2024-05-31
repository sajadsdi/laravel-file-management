<?php

namespace Sajadsdi\LaravelFileManagement\Events\Upload;


use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AfterUpload
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public array $config, public string $tempPath, public array $file)
    {

    }
}
