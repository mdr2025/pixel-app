<?php

namespace PixelApp\Interfaces\TenancyInterfaces;

use Stancl\Tenancy\Contracts\Tenant;

/**
 * it means : The central app's model needs to sync its data with tenant app's model
 */
interface NeedsTenantDataSync extends NeedsTenancyDataSync
{
    public function tenant(): Tenant ;
    public function getTenantAppModelClass(): string;
    public function getTenantAppModelIdentifierKeyName(): string;
    public function getTenantAppModelIdentifierOriginalValue();
}
