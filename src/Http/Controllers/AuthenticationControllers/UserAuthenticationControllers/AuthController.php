<?php

namespace PixelApp\Http\Controllers\AuthenticationControllers\UserAuthenticationControllers;
 
use PixelApp\Http\Controllers\PixelBaseController as Controller;
use Illuminate\Http\JsonResponse;
use PixelApp\Http\Resources\UserManagementResources\ModelResources\UserResource;
use PixelApp\Models\PixelBaseModel;
use PixelApp\Services\AuthenticationServices\UserAuthServices\EmailVerificationServices\UserEmailVerificationService;
use PixelApp\Services\AuthenticationServices\UserAuthServices\LoginService\LoginService;
use PixelApp\Services\AuthenticationServices\UserAuthServices\LogoutService\LogoutService;
use PixelApp\Services\AuthenticationServices\UserAuthServices\PasswordResettingService\PasswordResetNotificationSenderService;
use PixelApp\Services\AuthenticationServices\UserAuthServices\PasswordResettingService\PasswordResettingService;
use PixelApp\Services\AuthenticationServices\UserAuthServices\TokenRefreshingService\TokenRefreshingService;
use PixelApp\Services\AuthenticationServices\UserAuthServices\UserRegisteringServices\UserRegisteringService;

class AuthController extends Controller
{ 
    public function login() : JsonResponse
    {
        return (new LoginService())->login();
    }

    public function register(): JsonResponse
    {
        return (new UserRegisteringService())->create();
    }

    public function verifyEmail(): JsonResponse
    {
        return (new UserEmailVerificationService())->verify();
    }

    public function logout(): JsonResponse
    {
        return (new LogoutService())->logout();

    }

    public function forgetPassword(): JsonResponse
    {
        return (new PasswordResetNotificationSenderService())->send();
    }

    public function resetPassword(): JsonResponse
    { 
        return (new PasswordResettingService())->reset();
    }

    /**
     * @return JsonResponse
     */
    public function refreshToken(): JsonResponse
    {
        return (new TokenRefreshingService())->refreshToken();
    }

    public function getLoggedUser(): UserResource
    {
        /**
         * returns only user (main data , department , branch , role & permissions)
         * @var PixelBaseModel $loggedUser
         */ 
        $loggedUser = auth()->user();
        $loggedUser->load(["role:id,name", "role.permissions:name,id"]);
        return new UserResource(auth()->user($loggedUser));
    }
}
  //        AuthenticatableUserManagement
        //            Auth  :Login , Register , Password , Verification , Tokens , change-password
        //            Management :
        //                Signup =>  index , list , show , update (change email) , change status (accept , reject) , resend Verification  , export
        //                User =>  index , list , show , update (change other data without owner type changing) , change email , change status (active , inactive)  , export
