<?php

namespace Sajadsdi\LaravelFileManagement\Events;


use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BeforeMoveFile
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public array $config, public array $file, public string $moveDisk, public string $movePath)
    {

    }
}
