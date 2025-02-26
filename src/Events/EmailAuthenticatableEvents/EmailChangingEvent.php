<?php

namespace PixelApp\Events\EmailAuthenticatableEvents;

use PixelApp\Interfaces\EmailAuthenticatable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmailChangingEvent
{
    use Dispatchable  , InteractsWithSockets, SerializesModels;

    protected EmailAuthenticatable $authenticatable;
    protected string $oldEmail = "";

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct( EmailAuthenticatable $authenticatable , string $oldEmail = "")
    {
        $this->authenticatable = $authenticatable;
        $this->oldEmail = $oldEmail;
    }

    public function getAuthenticatable(): EmailAuthenticatable
    {
        return $this->authenticatable;
    }

    /**
     * @return string
     */
    public function getOldEmail(): string
    {
        return $this->oldEmail;
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
