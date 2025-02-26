<?php

namespace PixelApp\Http\Controllers\AuthenticationControllers\CompanyAuthenticationControllers;

use PixelApp\Http\Controllers\PixelBaseController as Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use PixelApp\Services\AuthenticationServices\CompanyAuthClientServices\CompanyCheckingCrNoService;
use PixelApp\Services\AuthenticationServices\CompanyAuthClientServices\CompanyCheckingStatusService;
use PixelApp\Services\AuthenticationServices\CompanyAuthClientServices\CompanyCheckingSubDomainService;
use PixelApp\Services\AuthenticationServices\CompanyAuthClientServices\CompanyForgettingIdService;
use PixelApp\Services\AuthenticationServices\CompanyAuthClientServices\CompanyLoginService;
use PixelApp\Services\AuthenticationServices\CompanyAuthClientServices\CompanyRegisteringService;
use PixelApp\Services\AuthenticationServices\CompanyAuthClientServices\DefaultAdminServices\EmailVerificationServices\DefaultAdminEmailVerificationService;

class CompanyAuthClientController extends Controller
{
    public function register() : JsonResponse
    { 
        return (new CompanyRegisteringService())->getResponse();
    }

    /**
     * @throws Exception
     */
    public function login() : JsonResponse
    {
        return (new CompanyLoginService())->getResponse();
    }

    public function verifyDefaultAdminEmail(): JsonResponse
    {
        return (new DefaultAdminEmailVerificationService())->getResponse();
    }

    public function checkStatus()
    {
        return (new CompanyCheckingStatusService())->getResponse();
    }

    /**
     * @throws Exception
     */
    public function forgetId() : JsonResponse
    {
         return (new CompanyForgettingIdService())->getResponse();
    } 

    public function checkSubDomain($subdomain) : JsonResponse
    {
        return (new CompanyCheckingSubDomainService($subdomain))->getResponse();
    }

    public function checkCrNo($crNo)  : JsonResponse
    { 
        return (new CompanyCheckingCrNoService($crNo))->getResponse();
    }
}
