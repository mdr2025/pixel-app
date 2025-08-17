<?php

namespace PixelApp\Events\EmailAuthenticatableEvents;

use PixelApp\Interfaces\EmailAuthenticatable;
use PixelApp\Models\SystemSettings\UsersModule\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmailAuthenticatableRegisteredEvent
{
    use Dispatchable , InteractsWithSockets, SerializesModels ;

    protected EmailAuthenticatable $authenticatable;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( EmailAuthenticatable $authenticatable)
    {
        $this->authenticatable = $authenticatable;
    }
    
    /**
     * @return User
     */
    public function getAuthenticatable(): EmailAuthenticatable
    {
        return $this->authenticatable;
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
