<?php

namespace PixelApp\Services\CompanyAccountServices\NormalCompanyAccountServices\CompanyProfileGettingService;

use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use PixelApp\Models\CompanyModule\CompanyAccountModels\CompanyAccount;
use PixelApp\Models\CompanyModule\PixelCompany\PixelCompany;
use PixelApp\Models\PixelModelManager;
use PixelApp\Services\CompanyAccountServices\BaseServices\CompanyProfileGettingService\CompanyProfileGettingBaseService;

class NormalCompanyProfileGettingService extends CompanyProfileGettingBaseService
{ 

    public function __construct()
    {
        BasePolicy::check( "read_company-account" );
    }

    protected function getCompanyAccountModelClass()  :string
    {
        return PixelModelManager::getModelForModelBaseType(CompanyAccount::class);
    }

    protected function fetchCompany() : ?PixelCompany
    {
        $companyClass = $this->getCompanyAccountModelClass();
        return $companyClass::first();
    }
    
    protected function loadCompanyRelations(PixelCompany $company) : void
    {
       $company->load(['defaultAdmin:id,name,created_at,hashed_id,email,mobile','country:id,name,code']);
    }

   protected function getSuccessResponse(PixelCompany $company) : JsonResponse
   {
        $this->loadCompanyRelations($company);

        return Response::success([ "item" => $company ]);
   }
}
