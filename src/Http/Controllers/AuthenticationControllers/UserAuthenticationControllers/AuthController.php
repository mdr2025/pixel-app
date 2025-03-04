<?php

namespace PixelApp\Http\Controllers\AuthenticationControllers\UserAuthenticationControllers;
 
use PixelApp\Http\Controllers\PixelBaseController as Controller;
use Illuminate\Http\JsonResponse;
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Http\Resources\UserManagementResources\ModelResources\UserResource;
use PixelApp\Models\PixelBaseModel;
use PixelApp\Services\AuthenticationServices\UserAuthServices\EmailVerificationServices\UserEmailVerificationService;
use PixelApp\Services\AuthenticationServices\UserAuthServices\LoginService\LoginService;
use PixelApp\Services\AuthenticationServices\UserAuthServices\LogoutService\LogoutService;
use PixelApp\Services\AuthenticationServices\UserAuthServices\PasswordResettingService\PasswordResetNotificationSenderService;
use PixelApp\Services\AuthenticationServices\UserAuthServices\PasswordResettingService\PasswordResettingService;
use PixelApp\Services\AuthenticationServices\UserAuthServices\TokenRefreshingService\TokenRefreshingService;
use PixelApp\Services\AuthenticationServices\UserAuthServices\UserRegisteringServices\UserRegisteringService;
use PixelApp\Services\PixelServiceManager;

class AuthController extends Controller
{ 
    public function login() : JsonResponse
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(LoginService::class);
        return (new $service())->login();
    }

    public function register(): JsonResponse
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(UserRegisteringService::class);
        return (new $service())->create();
    }

    public function verifyEmail(): JsonResponse
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(UserEmailVerificationService::class);
        return (new $service())->verify();
    }

    public function logout(): JsonResponse
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(LogoutService::class);
        return (new $service())->logout();

    }

    public function forgetPassword(): JsonResponse
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(PasswordResetNotificationSenderService::class);
        return (new $service())->send();
    }

    public function resetPassword(): JsonResponse
    { 
        $service = PixelServiceManager::getServiceForServiceBaseType(PasswordResettingService::class);
        return (new $service())->reset();
    }

    /**
     * @return JsonResponse
     */
    public function refreshToken(): JsonResponse
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(TokenRefreshingService::class);
        return (new $service())->refreshToken();
    }

    public function getLoggedUser(): UserResource
    {
        /**
         * returns only user (main data , department , branch , role & permissions)
         * @var PixelBaseModel $loggedUser
         */ 
        $loggedUser = auth()->user();
        $loggedUser->load(["role:id,name", "role.permissions:name,id"]);
        $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(UserResource::class);
        return new $resourceClass(auth()->user($loggedUser));
    }
}
  //        AuthenticatableUserManagement
        //            Auth  :Login , Register , Password , Verification , Tokens , change-password
        //            Management :
        //                Signup =>  index , list , show , update (change email) , change status (accept , reject) , resend Verification  , export
        //                User =>  index , list , show , update (change other data without owner type changing) , change email , change status (active , inactive)  , export
