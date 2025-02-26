<?php

namespace PixelApp\Events\TenancyEvents\TenantCompanyEvents;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Stancl\Tenancy\Events\Contracts\TenantEvent;

class TenantCompanyApproved extends TenantEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


}
