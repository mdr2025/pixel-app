<?php

namespace PixelApp\Http\Controllers\UserAccountControllers;

use PixelApp\Http\Controllers\PixelBaseController as Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use PixelApp\Http\Resources\UserAccountResources\UserProfileSpecificDataResource;
use PixelApp\Services\UserCompanyAccountServices\UserProfileUpdatingServices\UserProfileUpdatingService;
use PixelApp\Models\UsersModule\PixelUser;
use PixelApp\Services\UserCompanyAccountServices\PasswordChangerService\PasswordChangerService;

class UserProfileController extends Controller
{
    /**
     * @throws Exception
     */
    public function updateProfile(): JsonResponse
    {
        $user = auth()->user(); /** @var PixelUser $user */
        return (new UserProfileUpdatingService( $user ))->update();
    }

    public function changePassword(): JsonResponse
    {
        $user = auth()->user();
        return (new PasswordChangerService( $user ) )->change();
    }

    public function profile() : JsonResponse
    {
        /** @var PixelUser $user */
        $user= auth()->user();
        $user->load(['profile:user_id,logo,gender,country_id' ,'profile.country']);
        $data = [ "item" => new UserProfileSpecificDataResource( $user  )  ];
//        $data = [ "item" => new UserFullResource( $user  )  ];
        return Response::success($data);
    }

}
