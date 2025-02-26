<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\Traits;

trait CompanyDomainConstructable
{
    protected string $companyDomain;
    public function __construct(string $companyDomain)
    {
        $this->setCompanyDomain($companyDomain);
    }
    
    public function  setCompanyDomain(string $companyDomain)
    {
        $this->companyDomain = $companyDomain;
    }

}