<?php

namespace PixelApp\Policies\Dashboard;

use PixelApp\Exceptions\ExceptionTypes\JsonException;
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;

class DashboardPolicy extends BasePolicy
{
    /**
     * @return bool
     * @throws JsonException
     */
    public function read(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("read_dashboard")
            ->hasPermissionsOrFail();
    }

    /**
     * @return bool
     * @throws JsonException
     */
    public function readStatistics(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("read_dashboard-statistics")
            ->hasPermissionsOrFail();
    }

    /**
     * @return bool
     * @throws JsonException
     */
    public function readReports(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("read_dashboard-reports")
            ->hasPermissionsOrFail();
    }
}
