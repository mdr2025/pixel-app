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
        return $this->logOnFailureOnly(
                    callback: function()
                            {
                                $service = PixelServiceManager::getServiceForServiceBaseType(CompanyRegisteringService::class);
                                return (new $service())->create();
                            },
                    appendLoggedUserKeyToLog:false,
                    operationName : 'Tenant Company Registering Operation'
                );
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
        return $this->surroundWithTransaction(
                    function()
                    {
                        $service = PixelServiceManager::getServiceForServiceBaseType(DefaultAdminInfoSyncingPort::class);
                        return (new $service)->sync(); 
                    },
                    "Default Admin Data Syncing Operation"
                );
    }

    public function verifyDefaultAdminEmail(): JsonResponse
    {
         return $this->surroundWithTransaction(
                    function()
                    {
                        $service = PixelServiceManager::getServiceForServiceBaseType(DefaultAdminEmailVerificationService::class);
                        return (new $service())->verify();
                    },
                    "Default Admin Email Verifying Operation"
                );
    }

    public function checkStatus(): JsonResponse
    {
        return $this->logOnFailureOnly(
            callback : function()
            {
                $service = PixelServiceManager::getServiceForServiceBaseType(CompanyCheckingStatusService::class);
                return (new $service())->checkStatus();
            },
            operationName : 'Tenant Company Status Checking Operation'
        );
        
    }

    /**
     * @throws Exception
     */
    public function forgetId() : JsonResponse
    {
        return $this->logOnFailureOnly(
                    callback : function()
                    {
                        $service = PixelServiceManager::getServiceForServiceBaseType(CompanyForgettingIdService::class);
                        return (new $service())->resendCompanyId();
                    },
                    operationName : 'Tenant Company Id Resending Operation'
                );
    } 

    /**
     * For middleware operations
     */
    public function fetchCompany() : JsonResponse
    {
        return $this->logOnFailureOnly(
                    callback : function()
                    {
                        $service = PixelServiceManager::getServiceForServiceBaseType(CompanyFetchingService::class);
                        return (new $service())->getTenantCompanyDomainResponse();
                    },
                    operationName : 'Tenant Company Fetching Operation'
                );
        
    }
 
    public function fetchApprovedCompanyIDS() : JsonResponse
    {
        return $this->logOnFailureOnly(
                    callback : function()
                    {
                        $service = PixelServiceManager::getServiceForServiceBaseType(ApprovedTenantCompanyIDSFetchingService::class);
                        return (new $service())->getTenantCompanyIDS();
                    },
                    operationName : 'Approved Tenant Company Ids Fetching Operation'
                );   
    }
 
    public function checkSubDomain($subdomain) : JsonResponse
    {
        return $this->logOnFailureOnly(
                    callback : function() use ($subdomain)
                    {
                        $service = PixelServiceManager::getServiceForServiceBaseType(CompanyCheckingSubDomainService::class);
                        return (new $service())->checkSubDomainAvailability($subdomain);
                    },
                    operationName : 'Tenant Company Sub Domain Validaity Checking Operation'
                );        
    }

    public function checkCrNo($crNo)   : JsonResponse
    { 
        return $this->logOnFailureOnly(
            callback : function() use ($crNo)
            {
                $service = PixelServiceManager::getServiceForServiceBaseType(CompanyCheckingCrNoService::class);
                return (new $service)->checkCrNoValidity($crNo);
            },
            operationName : 'Tenant Company CR No Validaity Checking Operation'
        );
    }

}
