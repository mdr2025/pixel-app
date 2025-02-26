<?php

namespace PixelApp\Jobs\TenantCompanyJobs;

use PixelApp\Models\WorkSector\CompanyModule\TenantCompany;
use Stancl\Tenancy\Exceptions\DatabaseManagerNotRegisteredException;
use Stancl\Tenancy\Jobs\DeleteDatabase;

/**
 * @property TenantCompany $tenant
 */
class TenantDeletingDatabaseCustomJob extends DeleteDatabase
{

    /**
     * @throws DatabaseManagerNotRegisteredException
     */
    public function handle()
    {
        $databaseName = $this->tenant->tenancy_db_name ?? $this->tenant->getInternal("tenancy_db_name");
        if($this->tenant->database()->manager()->databaseExists($databaseName))
        {
            parent::handle();
        }
    }
}
