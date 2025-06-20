<?php

namespace PixelApp\Http\Controllers\AuthenticationControllers\CompanyAuthenticationControllers;

use PixelApp\Http\Controllers\PixelBaseController as Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\ApprovedTenantCompanyIDSFetchingService;
use PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\CompanyCheckingCrNoService;
use PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\CompanyCheckingSubDomainService;
use PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\CompanyFetchingService;
use PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\CompanyForgettingIdService;
use PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\CompanyLoginService;
use PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\CompanyRegisteringService;
use PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\CompanyCheckingStatusService;
use PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\DefaultAdminServices\EmailVerificationServices\DefaultAdminEmailVerificationService;
use PixelApp\Services\PixelServiceManager;
use PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\DefaultAdminServices\DefaultAdminInfoSyncingPort;
use PixelApp\Services\AuthenticationServices\CompanyAuthServerServices\DefaultAdminServices\DefaultAdminInfoUpdatingService;

class CompanyAuthServerController extends Controller
{
    public function register() : JsonResponse
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(CompanyRegisteringService::class);
        return (new $service())->create();
    }

    /**
     * @throws Exception
     */
    public function login() : JsonResponse
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(CompanyLoginService::class);
        return (new $service())->login();
    }
 
    public function updateDefaultAdminInfo() : JsonResponse
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(DefaultAdminInfoUpdatingService::class);
        return (new $service)->update();
    }

    public function syncDefaultAdminData() : JsonResponse
    {
        //temp func ... must developing a service later
        $service = PixelServiceManager::getServiceForServiceBaseType(DefaultAdminInfoSyncingPort::class);
        return (new $service)->sync(); 
    }

    public function verifyDefaultAdminEmail(): JsonResponse
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(DefaultAdminEmailVerificationService::class);
        return (new $service())->verify();
    }

    public function checkStatus(): JsonResponse
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(CompanyCheckingStatusService::class);
        return (new $service())->checkStatus();
    }

    /**
     * @throws Exception
     */
    public function forgetId() : JsonResponse
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(CompanyForgettingIdService::class);
         return (new $service())->resendCompanyId();
    } 

    /**
     * For middleware operations
     */
    public function fetchCompany() : JsonResponse
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(CompanyFetchingService::class);
        return (new $service())->getTenantCompanyDomainResponse();
    }
 
    public function fetchApprovedCompanyIDS() : JsonResponse
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(ApprovedTenantCompanyIDSFetchingService::class);
        return (new $service())->getTenantCompanyIDS();
    }
 
    public function checkSubDomain($subdomain) : JsonResponse
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(CompanyCheckingSubDomainService::class);
        return (new $service())->checkSubDomainAvailability($subdomain);
    }

    public function checkCrNo($crNo)   : JsonResponse
    { 
        $service = PixelServiceManager::getServiceForServiceBaseType(CompanyCheckingCrNoService::class);
       return (new $service)->checkCrNoValidity($crNo);
    }
}
