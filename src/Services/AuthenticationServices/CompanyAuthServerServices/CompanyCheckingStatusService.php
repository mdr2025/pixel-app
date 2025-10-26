<?php

namespace PixelApp\Services\AuthenticationServices\CompanyAuthServerServices;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Http\Requests\AuthenticationRequests\CompanyAuthenticationRequests\CheckStatusRequest;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Models\CompanyModule\TenantCompany;
use PixelApp\Services\Traits\GeneralValidationMethods;

class CompanyCheckingStatusService
{

    use GeneralValidationMethods;

    protected ?TenantCompany $company  = null;

    protected function getTenantCompanyModelClass() : string
    {
        return PixelTenancyManager::getTenantCompanyModelClass();
    }
 
    protected function getRequestFormClass() : string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(CheckStatusRequest::class);
    }
     
    function isVerified()
    {
        return (bool) $this->company->defaultAdmin->email_verified_at ?? false;
    }

    function getTenantRegistrationStatus()
    {
        return $this->company->status;
    }
    
    protected function fetchTenant() : ?TenantCompany
    {
        $modelClass = $this->getTenantCompanyModelClass();
        return $modelClass::whereHas('defaultAdmin', 
                                    function (Builder $query) 
                                    {
                                        $query->where('email', $this->data["admin_email"]);
                                    })->first();
    }
    
    protected function setTenantCompany() : void
    {
        $this->company = $this->fetchTenant();
    }

    public function checkStatus(): JsonResponse
    {
        $this->initValidator()->validateRequest()->setRequestData();
        $this->setTenantCompany();

        if(!$this->company)
        {
            return response()->json([
                "message" => "Your email is not registered in our database"
            ], 422);
        }

        if (!$this->isVerified()) 
        {
            return response()->json([
                "message" => "Your company email has not been verified yet"
            ]);
        } 
        
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
}
