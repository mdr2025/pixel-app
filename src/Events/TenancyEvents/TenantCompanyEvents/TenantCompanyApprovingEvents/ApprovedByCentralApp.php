<?php

namespace PixelApp\Events\TenancyEvents\TenantCompanyEvents\TenantCompanyApprovingEvents;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Stancl\Tenancy\Events\Contracts\TenantEvent;

class ApprovedByCentralApp extends TenantEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


}
