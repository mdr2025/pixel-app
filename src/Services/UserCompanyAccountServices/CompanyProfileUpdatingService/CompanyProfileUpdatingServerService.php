<?php

namespace PixelApp\Services\UserCompanyAccountServices\CompanyProfileUpdatingService; 
 
use CRUDServices\CRUDServiceTypes\DataWriterCRUDServices\DataWriterCRUDService;
use CRUDServices\CRUDServiceTypes\DataWriterCRUDServices\UpdatingServices\UpdatingService;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use PixelApp\CustomLibs\Tenancy\PixelTenancy;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Http\Requests\AuthenticationRequests\CompanyAuthenticationRequests\UpdateCompanyProfileRequest;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use Stancl\Tenancy\Contracts\Tenant;
use PixelApp\Models\CompanyModule\TenantCompany;

/**
 * @property TenantCompany | Tenant $Model
 */
class CompanyProfileUpdatingServerService extends UpdatingService
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
        $model = $class::where("company_domain" , $this->data["company_domain"])->first();
        if(!$model)
        {
            throw new Exception("There is not tenant company has this domain .");
        }

        $this->Model = $model;
    }
    
    protected function doBeforeOperationStart(): void
    {
        $this->setTenantModelByDomain();
    }

    protected function getRequestClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(UpdateCompanyProfileRequest::class);
    }

    protected function getModelUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update Your Company Account !";
    }

    protected function getModelUpdatingSuccessMessage(): string
    {
        return "Your Company Account Updated Successfully !";
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

    /**
     * @param array $dataRow
     * @param Model $model
     * @return DataWriterCRUDService
     *
     * We don't want to update any relationships by this service
     * Both defaultAdmin & package relationships must have their own updatingServices
     */
    protected function HandleModelRelationships(array $dataRow, Model $model): DataWriterCRUDService
    {
        return $this;
    }

    // public function update(): JsonResponse
    // {
    //     return tenancy()->central(function () {
    //         return parent::update();
    //     });
    // }
}
