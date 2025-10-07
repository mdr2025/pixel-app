<?php

namespace PixelApp\CustomLibs\Tenancy\DomainTenantResolvers;

use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use Stancl\Tenancy\Contracts\Tenant;
use Stancl\Tenancy\Exceptions\TenantCouldNotBeIdentifiedOnDomainException;
use Stancl\Tenancy\Resolvers\DomainTenantResolver;

class CustomDomainTenantResolver extends DomainTenantResolver
{
 
    protected function fetchTenant(string $domain) : ?Tenant
    {
        return PixelTenancyManager::fetchTenantForDomain($domain);
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
