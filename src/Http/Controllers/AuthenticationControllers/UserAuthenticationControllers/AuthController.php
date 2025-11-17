<?php

namespace PixelApp\Http\Controllers\AuthenticationControllers\UserAuthenticationControllers;

use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use PixelApp\Http\Controllers\PixelBaseController as Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Http\Resources\UserManagementResources\ModelResources\UserResource;
use PixelApp\Models\Interfaces\OptionalRelationsInterfaces\BelongsToBranch;
use PixelApp\Models\Interfaces\OptionalRelationsInterfaces\MustHaveRole;
use PixelApp\Models\PixelBaseModel;
use PixelApp\Models\UsersModule\UserProfile;
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
        BasePolicy::check("read_profile" , UserProfile::class);
        
        /**
         * returns only user (main data , department , branch , role & permissions)
         * @var PixelBaseModel $loggedUser
         */ 
        $loggedUser = Auth::user();
        $loggedUser->load(["role:id,name"]);
        
        if($loggedUser instanceof MustHaveRole)
        {
            $loggedUser->load("role.permissions:name,id");
        }
        
        if($loggedUser instanceof BelongsToBranch)
        {
            $loggedUser->load("branch");
        }

        $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(UserResource::class);
        return new $resourceClass(Auth::user($loggedUser));
    }
} 