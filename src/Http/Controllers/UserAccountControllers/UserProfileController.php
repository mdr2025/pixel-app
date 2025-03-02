<?php

namespace PixelApp\Http\Controllers\UserAccountControllers;

use PixelApp\Http\Controllers\PixelBaseController as Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Http\Resources\UserAccountResources\UserProfileSpecificDataResource;
use PixelApp\Services\UserCompanyAccountServices\UserProfileUpdatingServices\UserProfileUpdatingService;
use PixelApp\Models\UsersModule\PixelUser;
use PixelApp\Services\UserCompanyAccountServices\PasswordChangerService\PasswordChangerService;
use PixelAppCore\Services\PixelServiceManager;

class UserProfileController extends Controller
{
    /**
     * @throws Exception
     */
    public function updateProfile(): JsonResponse
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(UserProfileUpdatingService::class);
        $user = auth()->user(); /** @var PixelUser $user */
        return (new $service( $user ))->update();
    }

    public function changePassword(): JsonResponse
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(PasswordChangerService::class);
        $user = auth()->user();
        return (new $service( $user ) )->change();
    }

    public function profile() : JsonResponse
    {
        /** @var PixelUser $user */
        $user= auth()->user();
        $user->load(['profile:user_id,logo,gender,country_id' ,'profile.country']);
        $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(UserProfileSpecificDataResource::class);
        $data = [ "item" => new $resourceClass( $user  )  ];
//        $data = [ "item" => new UserFullResource( $user  )  ];
        return Response::success($data);
    }

}
