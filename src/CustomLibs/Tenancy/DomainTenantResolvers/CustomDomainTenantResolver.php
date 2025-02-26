<?php

namespace PixelApp\CustomLibs\Tenancy\DomainTenantResolvers;

use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Services\AuthenticationServices\CompanyAuthClientServices\CompanyFetchingService as CompanyFetchingClientService;
use PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\CompanyFetchingService;
use Stancl\Tenancy\Contracts\Tenant;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedOnDomainException;
use Stancl\Tenancy\Resolvers\DomainTenantResolver;

class CustomDomainTenantResolver extends DomainTenantResolver
{

    protected function fetchTenantForClientSide(string $domain) : ?Tenant
    {
        return (new CompanyFetchingClientService($domain))->fetchTenantCompany();
    }

    protected function fetchTenantForServerSide(string $domain) : ?Tenant
    {
        return (new CompanyFetchingService())->fetchTenantCompany($domain);
    }

    protected function fetchTenant(string $domain) : ?Tenant
    {
        if(PixelTenancyManager::isItAdminPanelApp() || PixelTenancyManager::isItMonolithTenancyApp())
        {
            return $this->fetchTenantForServerSide($domain);
        }
        return $this->fetchTenantForClientSide($domain);
    }
    /**
     * @throws TenantCouldNotBeIdentifiedOnDomainException
     */
    public function resolveWithoutCache(...$args): Tenant
    {
        $domain = $args[0];

        /** @var Tenant|null $tenant */
        $tenant = $this->fetchTenant($domain);

        if ($tenant)
        {
            $this->setCurrentDomain($tenant , $domain);
            return $tenant;
        }

        throw new TenantCouldNotBeIdentifiedOnDomainException($args[0]);
    }

    protected function setCurrentDomain(Tenant $tenant, string $domain): void
    {
        static::$currentDomain = $domain;
    }

    public function getArgsForTenant(Tenant $tenant): array
    {
        return [$tenant->domain];
    }
}
