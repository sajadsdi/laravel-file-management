<?php

namespace Sajadsdi\LaravelFileManagement\Events;


use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AfterMoveFile
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public array $config, public array $oldFile, public string $movedDisk, public string $movedPath)
    {

    }
}
