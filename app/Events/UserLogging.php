<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserLogging
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $user;
    public $action;
    public $ip_address;
    public $status;
    public $message;
    public $model;
    /**
     * Create a new event instance.
     */
    public function __construct($user, $action,$ip_address,$status,$message,$model)
    {
        $this->user = $user;
        $this->action = $action;
        $this->ip_address = $ip_address;
        $this->status = $status;
        $this->message = $message;
        $this->model = $model;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
