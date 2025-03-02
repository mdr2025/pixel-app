<?php

namespace PixelApp\Services\AuthenticationServices\CompanyAuthServerServices;


use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Http\Requests\AuthenticationRequests\CompanyAuthenticationRequests\CompanyIdForgettingRequest;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Models\CompanyModule\CompanyDefaultAdmin;
use PixelApp\Models\CompanyModule\TenantCompany;
use PixelApp\Models\PixelModelManager;
use PixelApp\Notifications\Company\TenantCompanyForgettingIdNotification;
use PixelApp\Services\Traits\GeneralValidationMethods;

class CompanyForgettingIdService
{
    use GeneralValidationMethods;

    protected ?CompanyDefaultAdmin $companyDefaultAdmin = null;
    protected ?TenantCompany $company = null;
    protected function getRequestFormClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(CompanyIdForgettingRequest::class);
    }

    protected function getResponse(): JsonResponse
    {
        $messages = ["Your company id has been sent to the company's default admin email !"];
        return Response::success([], $messages);
    }

    protected function sendDefaultAdminCompanyIdEmailMessage(): self
    {
        $this->companyDefaultAdmin->notify(new TenantCompanyForgettingIdNotification($this->company));
        return $this;
    }

    /**
     * @throws Exception
     */
    protected function fetchCompany(): ?TenantCompany
    {
        return PixelTenancyManager::getTenantCompanyModelClass()::find($this->companyDefaultAdmin->company_id);
    }

    /**
     * @throws Exception
     */
    protected function setCompany(): self
    {
        if (! $this->company = $this->fetchCompany()) {
            throw new Exception("There is no such company in our database !");
        }
        return $this;
    }

    /**
     * @return CompanyDefaultAdmin|null
     */
    protected function fetchCompanyAdmin(): ?CompanyDefaultAdmin
    {
        $modelClass = PixelModelManager::getModelForModelBaseType(CompanyDefaultAdmin::class);
        return $modelClass::where("email", $this->data["email"])->first();
    }

    protected function setCompanyDefaultAdmin(): self
    {
        if (! $this->companyDefaultAdmin = $this->fetchCompanyAdmin()) {
            throw new Exception("The entered admin email is not related to any company in our database !");
        }
        return $this;
    }

    /**
     * @throws Exception
     */
    public function resendCompanyId(): JsonResponse
    {
        $this->initValidator()->validateRequest()->setRequestData();
        $this->setCompanyDefaultAdmin()->setCompany()->sendDefaultAdminCompanyIdEmailMessage();
        return $this->getResponse();
    }
}
