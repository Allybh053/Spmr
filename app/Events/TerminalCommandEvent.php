<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TerminalCommandEvent implements ShouldBroadcast
{
    public $command;

    public function __construct($command)
    {
        $this->command = $command;
    }

    public function broadcastOn()
    {
        return new Channel('terminal');
    }
}
