<?php

namespace PixelApp\Jobs\RoleJobs;

use PixelApp\Services\SystemConfigurationServices\RolesAndPermissions\RoleUsersManagement\SwitchBackAllRolePreviousUsers;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Exception;

class SwitchBackAllRolePreviousUsersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private SwitchBackAllRolePreviousUsers $service;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SwitchBackAllRolePreviousUsers $service)
    {
        $this->service = $service;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        $this->service->updateUsersRole();
    }
}
