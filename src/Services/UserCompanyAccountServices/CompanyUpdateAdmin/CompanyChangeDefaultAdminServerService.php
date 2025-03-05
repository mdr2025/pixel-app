<?php

namespace PixelApp\Services\UserCompanyAccountServices\CompanyUpdateAdmin;

use Exception;
use Illuminate\Support\Facades\DB;
use PixelApp\Exceptions\CustomJsonException;
use PixelApp\Exceptions\JsonException;
use PixelApp\Models\CompanyModule\CompanyDefaultAdmin;
use PixelApp\Models\CompanyModule\TenantCompany;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\UsersModule\PixelUser;
use PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\CompanyFetchingService;
use Stancl\Tenancy\Contracts\Tenant;

/**
 * @todo to review later
 */
class CompanyChangeDefaultAdminServerService
{
    protected TenantCompany $tenant ;
    protected CompanyDefaultAdmin $oldAdmin;
    protected PixelUser $userAdmin;
    protected CompanyDefaultAdmin $admin;
    protected $request;

    public function __construct()
    {
        $this->setTenant();
        $this->setOldAdmin();
        $this->setUserAdmin();
        $this->setRequest();
    }

    protected function fetchTenantByDomain(string $companyDomain) : ?Tenant
    {
        return (new CompanyFetchingService())->fetchTenantCompany($companyDomain);
    }

    protected function setTenant() : void
    {
        $companyDomain = $this->getRequest()->input("compay_domain");
        if(!$companyDomain || !$tenant = $this->fetchTenantByDomain($companyDomain))
        {
            throw new Exception("There is no tenant has this domain");
        }

        $this->tenant = $tenant;
    }

    protected function getTenant() : Tenant
    {
        return $this->tenant;
    }

    protected function setRequest(): void
    {
        $this->request = request();
    }

    /**
     * @return mixed
     */
    protected function getRequest()
    {
        return $this->request;
    }

    protected function getOldAdmin(): CompanyDefaultAdmin
    {
        return $this->oldAdmin;
    }

    protected function setOldAdmin(): void
    {
        $this->oldAdmin = CompanyDefaultAdmin::where('company_id', $this->getTenant()->id)->first();
    }

    protected function getUserAdmin(): PixelUser
    {
        return $this->userAdmin;
    }

    protected function getUserModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }

    protected function setUserAdmin(): void
    {
        $this->userAdmin = $this->tenant->run(
                            function()
                            {
                                return $this->getUserModelClass()::where('email', $this->getOldAdmin()->email)->first();
                            });
    }

    protected function getAdmin(): CompanyDefaultAdmin
    {
        return $this->admin;
    }

    protected function setAdmin(PixelUser $admin): void
    {
        $this->admin =  CompanyDefaultAdmin::create(
            [
                'email' => $admin->email,
                'name' => $admin->name,
                'first_name' => $admin->first_name,
                'last_name' => $admin->last_name,
                'password' => $admin->password,
                'mobile' => $admin->mobile,
                'company_id' => tenant()->id
            ]
        );
    }

    /**
     * @throws  JsonException
     */
    protected function checkAdminRole(): self
    {
        if (auth()->user()->role_id != 1) {
            throw new JsonException("you can't change admin email");
        }
        return $this;
    }

    protected function updateRoleToPreviousAdmin(): self
    {
        $oldUser = $this->getUserAdmin();
        $request = $this->getRequest();
        if ($oldUser->id <> $request->user_id) {
            $oldUser->role_id = $request->role_id;
            $oldUser->save();
        }
        return $this;
    }
    protected function deletePreviousAdmin(): self
    {
        $this->getOldAdmin()->delete();
        return $this;
    }
    protected function updateNewAdminRole(): self
    {
        $request = $this->getRequest();
        $admin = $this->getUserModelClass()::findOrFail($request->user_id);
        $admin->role_id = 1;
        $admin->save();
        return $this;
    }

    public function update()
    {

        /**
         * @todo need to review ... it doesn't work for now
         */
        // try {
        //     DB::beginTransaction();
        //     $this->checkAdminRole()->updateRoleToPreviousAdmin()->updateNewAdminRole()->deletePreviousAdmin()->setAdmin($this->getUserAdmin());
        //     DB::commit();
            return ["message" => "Company Admin Has Been Updated"];
        // } catch (\Throwable $e) {
        //     DB::rollBack();
        //     throw $e;
        // }
    }
}
