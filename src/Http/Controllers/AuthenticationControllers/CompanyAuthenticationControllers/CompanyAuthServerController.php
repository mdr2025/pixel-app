<?php

namespace PixelApp\Http\Controllers\AuthenticationControllers\CompanyAuthenticationControllers;

use PixelApp\Http\Controllers\PixelBaseController as Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\CompanyCheckingCrNoService;
use PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\CompanyCheckingSubDomainService;
use PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\CompanyFetchingService;
use PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\CompanyForgettingIdService;
use PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\CompanyLoginService;
use PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\CompanyRegisteringService;
use PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\CompanyCheckingStatusService;
use PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\DefaultAdminServices\EmailVerificationServices\DefaultAdminEmailVerificationService;

class CompanyAuthServerController extends Controller
{
    public function register() : JsonResponse
    {
        return (new CompanyRegisteringService())->create();
    }

    /**
     * @throws Exception
     */
    public function login() : JsonResponse
    {
        return (new CompanyLoginService())->login();
    }

    public function verifyDefaultAdminEmail(): JsonResponse
    {
        return (new DefaultAdminEmailVerificationService())->verify();
    }

    public function checkStatus(): JsonResponse
    {
        return (new CompanyCheckingStatusService())->checkStatus();
    }

    /**
     * @throws Exception
     */
    public function forgetId() : JsonResponse
    {
         return (new CompanyForgettingIdService())->resendCompanyId();
    } 

    /**
     * For middleware operations
     */
    public function fetchCompany() : JsonResponse
    {
        return (new CompanyFetchingService())->getTenantCompanyDomainResponse();
    }
 
    public function checkSubDomain($subdomain) : JsonResponse
    {
        return (new CompanyCheckingSubDomainService())->checkSubDomainAvailability($subdomain);
    }

    public function checkCrNo($crNo)   : JsonResponse
    { 
       return (new CompanyCheckingCrNoService)->checkCrNoValidity($crNo);
    }
}
