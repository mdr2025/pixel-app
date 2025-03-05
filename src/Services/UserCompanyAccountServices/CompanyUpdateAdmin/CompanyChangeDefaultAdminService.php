<?php

namespace PixelApp\Services\UserCompanyAccountServices\CompanyUpdateAdmin;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use JsonException;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\ClientBaseServices\AdminPanelConnectingClientService;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppClients\PixelAdminPanelAppClient;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\CompanyAuthRouteIdentifierFactories\CompanyDefaultAdminRouteIdentifierFactory;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppsConnectionManager;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Events\TenancyEvents\TenantModelDataSyncNeedEvent;
use PixelApp\Http\Requests\UserAccountRequests\CompanyDefaultAdminUpdatingRequest;
use PixelApp\Models\CompanyModule\CompanyDefaultAdmin;
use PixelApp\Models\CompanyModule\TenantCompany;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\UsersModule\PixelUser;
use PixelApp\Services\Traits\GeneralValidationMethods;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\UserRoleChanger;
use Stancl\Tenancy\Contracts\Tenant;

class CompanyChangeDefaultAdminClientService  
{
    use GeneralValidationMethods;
 
    protected ?UserRoleChanger $userRoleChanger = null;
    protected TenantCompany $tenant ;
    protected CompanyDefaultAdmin $oldAdmin;
    protected PixelUser $tenantDefaultUserAdmin;
    protected PixelUser $newAdminUser;
    protected CompanyDefaultAdmin $admin; 

    public function __construct()
    {
        $this->setTenant();
        $this->setOldDefaultAdmin();
        $this->setTenantDefaultUserAdmin(); 
    }

    protected function getRequestFormClass() : string
    {
        return CompanyDefaultAdminUpdatingRequest::class;
    }
 
    protected function setTenant() : void
    {
        $this->tenant = tenant();
    }

    protected function getTenant() : TenantCompany
    {
        return $this->tenant;
    }
  
    protected function getOldDefaultAdmin(): CompanyDefaultAdmin
    {
        return $this->oldAdmin;
    }

    protected function setOldDefaultAdmin(): void
    {
        $this->oldAdmin = $this->getTenant()->defaultAdmin;
    }

    protected function getTenantDefaultUserAdmin(): PixelUser
    {
        return $this->tenantDefaultUserAdmin;
    }

    protected function getUserModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }

    protected function fetchfetchDefaultAdminInTenantDB() : ?PixelUser
    {
        $userModelClalss = $this->getUserModelClass();
        return $userModelClalss::where('email', $this->getOldDefaultAdmin()->email)->first();
    }
    protected function setTenantDefaultUserAdmin(): void
    {
        if(!$user = $this->fetchfetchDefaultAdminInTenantDB())
        {
            throw new JsonException("There is no default admin has this email in the system");
        }

        $this->tenantDefaultUserAdmin =  $user; 
    }

    // protected function getAdmin(): CompanyDefaultAdmin
    // {
    //     return $this->admin;
    // }

    // protected function setAdmin(PixelUser $admin): void
    // {
    //     $this->admin =  CompanyDefaultAdmin::create(
    //         [
    //             'email' => $admin->email,
    //             'name' => $admin->name,
    //             'first_name' => $admin->first_name,
    //             'last_name' => $admin->last_name,
    //             'password' => $admin->password,
    //             'mobile' => $admin->mobile,
    //             'company_id' => tenant()->id
    //         ]
    //     );
    // }

    protected function isAdminAssigningHimSelf() : bool
    {  
        return $this->getTenantDefaultUserAdmin()->id == $this->data["user_id"];
    }
    /**
     * @throws  JsonException
     */
    protected function checkActionImplementerUserPermissions(): self
    {
        /** @var PixelUser $user  */
        $user = auth()->user();
        if (!$user->isDefaultUser()) 
        {
            throw new JsonException("youdon't have the permissions required to change the system 's default admin email");
        }

        return $this;
    }

    protected function checkActionImplementerUser() : self
    {
        $this->checkActionImplementerUserPermissions();

        if($this->isAdminAssigningHimSelf())
        {
            throw new JsonException("The selected user admin is already the default admin !");
        }

        return $this;
    }

    protected function initUserRoleChanger() : UserRoleChanger
    {
        if(!$this->userRoleChanger)
        {
            $this->userRoleChanger = new UserRoleChanger();
        }
        return $this->userRoleChanger;
    }

    protected function fetchNewAdminInTenantDb() : ?PixelUser
    {
        return $this->getUserModelClass()::findOrFail($this->data["user_id"]);
    }
    
    protected function setNewDefaultAdminUser() : void
    { 
        $this->newAdminUser = $this->fetchNewAdminInTenantDb();
    }
    protected function updateNewAdminRole(): self
    {
        $this->setNewDefaultAdminUser() ;
        $roleChanges = ["role_id" => 1];
        $this->initUserRoleChanger()->setAuthenticatable($this->newAdminUser)->setData($roleChanges)->changeAuthenticatableProp();
        $this->newAdminUser->save();
        return $this;
    }

    /**
     * ask later 
     * if the the request user id == user id
     * it will not be changed but the new admnin will be assigned as a default admin also
     * there will be may super admins and many defualt admins ... it is not implemented for now but ask later
     */
    protected function updatePreviousAdminRole(): self
    {
        $user = $this->getTenantDefaultUserAdmin();
        $this->initUserRoleChanger()->setAuthenticatable($user)->setData($this->data)->changeAuthenticatableProp();
        $user->save();
        return $this;
    }

    protected function defaultAdminUpdatingInfoRouteIdentifierFactory() : PixelAppRouteIdentifierFactory
    {
        return new CompanyDefaultAdminRouteIdentifierFactory($this->getTenant()->domain);
    }
     
    protected function syncForTenantApp() : JsonResponse
    {
        $adminPAnelClientName = PixelAdminPanelAppClient::getClientName();
        $routeIdentifier = $this->defaultAdminUpdatingInfoRouteIdentifierFactory()->createRouteIdentifier();
        return PixelAppsConnectionManager::Singleton()->connectOn($adminPAnelClientName )->requestOnRoute($routeIdentifier);
    }

    protected function sycnForMonolithApp() : void
    {
        event(new TenantModelDataSyncNeedEvent($this->newAdminUser));
    }
    protected function syncDataWithAdminPanel(): void
    {
        if(PixelTenancyManager::isItMonolithTenancyApp() && $this->newAdminUser->canSyncData())
        {
            $this->sycnForMonolithApp();
        }

        if(PixelTenancyManager::isItTenantApp())
        {
            $this->syncForTenantApp();
        }
    }

    protected function switchAdminsRoles() : self
    {
        return $this->updatePreviousAdminRole()->updateNewAdminRole();
    }

    public function update()
    {
        $this->initValidator()->validateRequest()->setRequestData();

        $this->checkActionImplementerUser(); 
        try {
            DB::beginTransaction();
            $this->switchAdminsRoles();
            DB::commit();
            
            return Response::success([] , ["message" => "Company Admin Has Been Updated"]);
        } catch (\Throwable $e)
        {
            DB::rollBack();
            return REsponse::error($e->getMessage());
        }
    }

    
}
