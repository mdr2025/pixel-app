<?php

namespace PixelApp\Services\CompanyAccountServices\TenantCompanyAccountServices\CompanyProfileUpdatingService;
 

use Exception; 
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Http\Requests\CompanyAccountRequests\TenantCompanyAccountRequests\UpdateCompanyProfileRequest;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use Stancl\Tenancy\Contracts\Tenant;
use PixelApp\Models\CompanyModule\TenantCompany;
use PixelApp\Services\CompanyAccountServices\BaseServices\CompanyProfileUpdatingService\CompanyProfileUpdatingBaseService;

/**
 * @property TenantCompany | Tenant $Model
 */
class CompanyProfileUpdatingServerService extends CompanyProfileUpdatingBaseService
{

    public function __construct()
    {
        parent::__construct($this->initDefaultTenantModel());
    }

    protected function getTenantCompanyModelClass() : string
    {
        return PixelTenancyManager::getTenantCompanyModelClass();
    }

    protected function initDefaultTenantModel() : TenantCompany
    {
        $class = $this->getTenantCompanyModelClass();
        return new $class();
    }

    protected function setTenantModelByDomain() : void
    {
        $class = $this->getTenantCompanyModelClass();
        $model = $class::where("domain" , $this->data["company_domain"])->first();

        if(!$model)
        {
            throw new Exception("There is not tenant company has this domain .");
        }

        $this->Model = $model;
         
    }
    
    protected function onAfterDbTransactionStart(): void
    {
        $this->setTenantModelByDomain();
    }

    protected function getRequestClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(UpdateCompanyProfileRequest::class);
    }
  
    protected function removeOneTimeFillingRequestFields(): void
    {
        foreach ($this->Model->getOneTimeFillingAttrs() as $attrName) {
            request()->request->remove($attrName);
        }
    }

    protected function doBeforeValidation(): void
    {
        $this->removeOneTimeFillingRequestFields();;
    }
 
}
