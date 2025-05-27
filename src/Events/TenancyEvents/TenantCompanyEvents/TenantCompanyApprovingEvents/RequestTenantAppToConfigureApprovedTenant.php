<?php

namespace PixelApp\Events\TenancyEvents\TenantCompanyEvents\TenantCompanyApprovingEvents;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RequestTenantAppToConfigureApprovedTenant
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected string $companyDomain ;

    public function __construct(string $companyDomain)
    {
        $this->setCompanyDomain($companyDomain);    
    }

    public function setCompanyDomain(string $companyDomain) : self
    {
        $this->companyDomain = $companyDomain;
        return $this;
    }

    public function getCompanyDomain() : string
    {
        return $this->companyDomain;
    }

}
