<?php

namespace PixelApp\Services\AuthenticationServices\CompanyAuthServerServices;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Http\Requests\AuthenticationRequests\CompanyAuthenticationRequests\CheckStatusRequest;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Interfaces\EmailAuthenticatable;
use PixelApp\Models\CompanyModule\CompanyDefaultAdmin;
use PixelApp\Models\CompanyModule\TenantCompany;
use PixelApp\Models\PixelModelManager;
use PixelApp\Services\Traits\GeneralValidationMethods;

class CompanyCheckingStatusService
{

    use GeneralValidationMethods;

    protected CompanyDefaultAdmin $tenantCompanyDefaultAdmin;
    protected ?TenantCompany $company  = null;

    protected function getTenantCompanyModelClass() : string
    {
        return PixelTenancyManager::getTenantCompanyModelClass();
    }
 
    protected function getRequestFormClass() : string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(CheckStatusRequest::class);
    }

    protected function responeBasedOnRegistationsStatus() : JsonResponse
    {
        if ($this->getTenantRegistrationStatus() == $this->company->getApprovingStatusValue() ) 
        {
            return response()->json([
                "message" => "Your company account has been approved , kindly check your email for company id"
            ], 422);

        } elseif ($this->getTenantRegistrationStatus() == $this->company->getRejectedStatusValue()) 
        {
            return response()->json([
                "message" => "Your company account has been rejected , kindly contact support team"
            ], 422);
        }else
        {
            //in this case status == "pending"
            return response()->json([
                "message" => "Your company account is not approved "
            ], 422);

        }
    }
     
    function isDefaultAdminVerified() : bool
    {
        return $this->company->defaultAdmin->isVerified();
    }

    protected function checkVerificationStatus() : void
    {
        if (!$this->isDefaultAdminVerified()) 
        {
            throw new Exception("Your company email has not been verified yet");
        } 
    }

    function getTenantRegistrationStatus()
    {
        return $this->company->status;
    }
    
    protected function fetchTenant() : ?TenantCompany
    {
        return $this->tenantCompanyDefaultAdmin->tenant;
    }
    
    protected function setTenantCompany() : self
    {
        
        $this->company = $this->fetchTenant();

        if(!$this->company)
        {
            throw new Exception("The used email is not related to any of tenant companies !");
        }

        return $this;
    }
    protected function getCompanyDefaultAdminModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(CompanyDefaultAdmin::class);
    }

    protected function fetchTenantCompanyDefaultAdmin() : ?CompanyDefaultAdmin
    {
        return $this->getCompanyDefaultAdminModelClass()::query()->where('email', $this->data["admin_email"])->first();
    }

    protected function setTenantCompanyDefaultAdmin() : self
    {
        $defaultAdmin = $this->fetchTenantCompanyDefaultAdmin();

        if(!$defaultAdmin)
        {
            throw new Exception("Your email is not registered in our database");
        }

        $this->tenantCompanyDefaultAdmin = $defaultAdmin;

        return $this;
    }
    protected function setTenantCompanyObjects() : self
    {
        return $this->setTenantCompanyDefaultAdmin()->setTenantCompany();
    }

    public function checkStatus(): JsonResponse
    {
        $this->initValidator()->validateRequest()->setRequestData();

        try
        {
            $this->setTenantCompanyObjects()->checkVerificationStatus();

            //the company is verified at this point

            return $this->responeBasedOnRegistationsStatus();

        }catch(Exception $e)
        {
            return Response::error($e->getMessage() , 422);
        }
        
    }
}
