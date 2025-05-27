<?php

namespace PixelApp\Events\TenancyEvents\TenantCompanyEvents\TenantCompanyApprovingEvents;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use PixelApp\Models\CompanyModule\TenantCompany;
use Throwable;

class TenantConfiguringCancelingEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected TenantCompany $tenant ;
    protected ?Throwable $failingException = null;

    public function __construct(TenantCompany $tenant , ?Throwable $failingException = null)
    {
        $this->tenant = $tenant ;
        $this->failingException = $failingException;
    }

    /**
     * Get the tenant company
     */
    public function getTenant(): TenantCompany
    {
        return $this->tenant;
    }

    /**
     * Set the tenant company
     * 
     * @return $this
     */
    public function setTenant(TenantCompany $tenant): self
    {
        $this->tenant = $tenant;
        return $this;
    }

    /**
     * Get the failing exception (nullable)
     */
    public function getFailingException(): ?Throwable
    {
        return $this->failingException;
    }

    /**
     * Set the failing exception
     * 
     * @return $this
     */
    public function setFailingException(?Throwable $failingException): self
    {
        $this->failingException = $failingException;
        return $this;
    }
}
