<?php

namespace PixelApp\Jobs\RoleJobs;

 use Exception; 
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use PixelApp\Services\SystemConfigurationServices\RolesAndPermissions\RoleUsersManagement\SwitchAllRoleUsersToDefaultRole;  

class SwitchAllRoleUsersToDefaultRoleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private SwitchAllRoleUsersToDefaultRole $service;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(SwitchAllRoleUsersToDefaultRole $service)
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
