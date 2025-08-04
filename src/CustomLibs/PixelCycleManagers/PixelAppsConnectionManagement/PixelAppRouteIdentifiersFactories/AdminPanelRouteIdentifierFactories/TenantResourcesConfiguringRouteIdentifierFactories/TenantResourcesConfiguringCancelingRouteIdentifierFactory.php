<?php

namespace PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\TenantResourcesConfiguringRouteIdentifierFactories;

use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppPostRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiers\PixelAppRouteIdentifier;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;
use Throwable;

class TenantResourcesConfiguringCancelingRouteIdentifierFactory extends PixelAppRouteIdentifierFactory
{ 

    protected string $companyDomain;
    protected ?Throwable $failingException = null;

    public function __construct(string $companyDomain , ?Throwable $failingException = null)
    {
        $this->companyDomain = $companyDomain;
        $this->failingException = $failingException;
    }

    protected function getRouteRequriedData() : array
    {
        $data = ["company_domain" =>  $this->companyDomain];

        if($this->failingException)
        {
            $data["message"] = $this->failingException->getMessage();
            $data["code"] = $this->failingException->getCode();
        }

        return $data;
    }

    protected function composeRequestPostData() : array
    {
        return array_merge( request()->all() , $this->getRouteRequriedData() );
    }

    protected function getUri() : string
    {
        return "api/company/cancel-resources-configuring";
    }

    public function createRouteIdentifier()  :PixelAppRouteIdentifier
    {
        return new PixelAppPostRouteIdentifier($this->getUri() , $this->composeRequestPostData());
    }
}