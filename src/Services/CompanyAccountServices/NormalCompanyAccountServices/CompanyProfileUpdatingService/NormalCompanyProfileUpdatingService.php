<?php

namespace PixelApp\Services\CompanyAccountServices\NormalCompanyAccountServices\CompanyProfileUpdatingService;

use Exception; 
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Models\CompanyModule\CompanyAccountModels\CompanyAccount;
use PixelApp\Models\CompanyModule\PixelCompany\PixelCompany;
use PixelApp\Models\PixelModelManager;
use PixelApp\Services\CompanyAccountServices\BaseServices\CompanyProfileUpdatingService\CompanyProfileUpdatingBaseService;
use PixelApp\Http\Requests\CompanyAccountRequests\NormalCompanyAccountRequests\UpdateCompanyProfileRequest;

/**
 * @property PixelCompany $Model
 */
class NormalCompanyProfileUpdatingService extends CompanyProfileUpdatingBaseService
{

    public function __construct()
    {
        parent::__construct($this->getCompanyAccountModel());
    }

    protected function getCompanyAccountModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(CompanyAccount::class);
    } 

    protected function getCompanyAccountModel() : CompanyAccount
    {
        $companyAccountClass = $this->getCompanyAccountModelClass();
        return $companyAccountClass::first()
               ??
               throw new Exception("Missed data .... there is no company account to updated !");
    }

    protected function getRequestClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(UpdateCompanyProfileRequest::class);
    }

    protected function loadCompanyRelations() : void
    {
        $this->Model->loadDefaultAdmin();
    }

    protected function getSuccessResponseData(): array
    {
        return [$this->Model];
    }
}
