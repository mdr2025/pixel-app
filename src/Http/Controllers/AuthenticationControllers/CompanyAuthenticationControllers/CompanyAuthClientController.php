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
use PixelApp\Services\PixelServiceManager;

class CompanyAuthClientController extends Controller
{
    public function register() : JsonResponse
    { 
        $service = PixelServiceManager::getServiceForServiceBaseType(CompanyRegisteringService::class);
        return (new $service())->getResponse();
    }

    /**
     * @throws Exception
     */
    public function login() : JsonResponse
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(CompanyLoginService::class);
        return (new $service())->getResponse();
    }
 
    public function checkStatus()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(CompanyCheckingStatusService::class);
        return (new $service())->getResponse();
    }

    /**
     * @throws Exception
     */
    public function forgetId() : JsonResponse
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(CompanyForgettingIdService::class);
         return (new $service())->getResponse();
    } 

    public function checkSubDomain($subdomain) : JsonResponse
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(CompanyCheckingSubDomainService::class);
        return (new $service($subdomain))->getResponse();
    }

    public function checkCrNo($crNo)  : JsonResponse
    { 
        $service = PixelServiceManager::getServiceForServiceBaseType(CompanyCheckingCrNoService::class);
        return (new $service($crNo))->getResponse();
    }
}
