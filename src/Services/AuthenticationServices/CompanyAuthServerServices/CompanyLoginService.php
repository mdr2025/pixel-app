<?php

namespace PixelApp\Services\AuthenticationServices\CompanyAuthServerServices;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Http\Requests\AuthenticationRequests\CompanyAuthenticationRequests\CompanyLoginRequest;
use PixelApp\Http\Resources\AuthenticationResources\CompanyAuthenticationResources\ModelsResources\TenantCompanyResource;
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Models\CompanyModule\TenantCompany;
use PixelApp\Services\Traits\GeneralValidationMethods;

class CompanyLoginService
{
    use GeneralValidationMethods;
    protected TenantCompany $company;

    protected function getRequestFormClass(): string
    {
        return CompanyLoginRequest::class;
    }

    protected function getLoginResponse(): JsonResponse
    {
        $resource = PixelHttpResourceManager::getResourceForResourceBaseType(TenantCompanyResource::class);
        $data = (new $resource($this->company))->toArray(request());
        $messages = ["Successful login , Welcome to company " . $this->company->name ];
        return Response::success($data, $messages);
    }

    /**
     * @throws Exception
     */
    protected function checkCompanyApprovment(): self
    {
        if (!$this->company->isApproved())
        {
            throw new Exception("This company account is not approved yet !");
        }
        return $this;
    }
  
    protected function fetchCompanyById(string $companyId) : ?TenantCompany
    {
        $tenantModelClass = PixelTenancyManager::getTenantCompanyModelClass();
        return $tenantModelClass::where("company_id", $companyId)->first();
    }
    /**
     * @return $this
     * @throws Exception
     */
    protected function setCompany(): self
    {
        $companyId = $this->data["company_id"];
        // $this->CheckRcNo($companyId); 
        
        if ($company = $this->fetchCompanyById($companyId))
        {
            $this->company = $company;
            return $this;
        }
        throw new Exception("There is no such company in our database !");
    }

    // private function CheckRcNo($companyId): void
    // {
    //     $tenantModelClass = PixelTenancyManager::getTenantCompanyModelClass();
    //     $companyWithRcNo = $tenantModelClass::where("cr_no", $companyId)->first();

    //     if ($companyWithRcNo) {
    //         throw new \Exception("You must log in using Company ID not CR No.");
    //     }
    // }

    /**
     * @return $this
     */
    function setCompanySlug(): self
    {
        if ($this->company) {
            $this->company['slug'] = Str::slug($this->company->name);
        }
        return $this;
    }

    /**
     * @throws Exception
     */
    public function login(): JsonResponse
    {
        $this->initValidator()->validateRequest()->setRequestData();
        $this->setCompanyId()->setCompany()->setCompanySlug()->checkCompanyActivity()->checkCompanyApprovment();
        return $this->getLoginResponse();
    }

    private function isValidCompanyId(string $companyId): bool
    {
        $companyPattern = '/^CO-\d{4,8}$/';
        return (bool) (preg_match($companyPattern, $companyId));
    }

    /**
     * @throws Exception
     */
    private function setCompanyId(): self
    {
        $companyId = Str::upper($this->data['company_id']);

        if ($this->isValidCompanyId($companyId)) {
            $this->data['company_id'] = $companyId;
            return $this;
        }

        if (is_numeric($companyId) && strlen($companyId) >= 4 && strlen($companyId) <= 8) {
            $this->data['company_id'] = 'CO-' . $companyId;
            return $this;
        }

        throw new Exception('Invalid Requested Company Id');
    }
}
