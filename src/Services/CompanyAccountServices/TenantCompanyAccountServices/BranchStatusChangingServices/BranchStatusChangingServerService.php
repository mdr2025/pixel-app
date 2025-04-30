<?php

namespace PixelApp\Services\CompanyAccountServices\TenantCompanyAccountServices\BranchStatusChangingServices;
 
use Illuminate\Http\JsonResponse; 
use Illuminate\Support\Facades\Response;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager; 
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\CompanyAccountRequests\TenantCompanyAccountRequests\ChangeBranchCompanyStatusRequest;
use PixelApp\Models\CompanyModule\TenantCompany; 
use PixelApp\Services\Traits\GeneralValidationMethods; 

class BranchStatusChangingServerService 
{    
    use GeneralValidationMethods;

    protected int $tenantBranchId;
    public function __construct(int $tenantBranchId)
    {
        $this->tenantBranchId = $tenantBranchId;
    }
    protected function getRequestFormClass() : string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(ChangeBranchCompanyStatusRequest::class);
    }
    protected function getTenantCompanyModelClass() : string
    {
        return PixelTenancyManager::getTenantCompanyModelClass();
    }
    protected function getStatusRequestValue() : string
    {
        return $this->data["status"];
    }
    protected function getTenantBranch() : ?TenantCompany
    {
        return $this->getTenantCompanyModelClass()::find($this->tenantBranchId);
    }

    public function change() : JsonResponse
    {
        $this->initValidator()->validateRequest()->setRequestData();
        $tenantBranch = $this->getTenantBranch();
        $status = $this->getStatusRequestValue(); 

        if (!$tenantBranch)
        {
            return Response::error(["Company not found."]);
        }

        if ($tenantBranch->main_company_PixelApproved_status !== 'pending')
        {
            return Response::error(["Status cannot be changed."]);
        }

        $tenantBranch->main_company_PixelApproved_status = $status;
        $tenantBranch->save();
        return Response::success([], ["Status changed successfully."]);
    }
              
}
