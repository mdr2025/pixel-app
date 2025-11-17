<?php

namespace PixelApp\Policies;

use App\Exceptions\JsonException;
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;

class NotificationPolicy extends BasePolicy
{
    /**
     * @return bool
     * @throws JsonException
     */
    public function read(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("read_notifications")
            ->hasPermissionsOrFail();
    }

    /**
     * @return bool
     * @throws JsonException
     */
    public function markAsRead(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("mark-as-read_notifications")
            ->hasPermissionsOrFail();
    }

    /**
     * @return bool
     * @throws JsonException
     */
    public function delete(): bool
    {
        return $this->permissionExaminer->addPermissionToCheck("delete_notifications")
            ->hasPermissionsOrFail();
    }
}
