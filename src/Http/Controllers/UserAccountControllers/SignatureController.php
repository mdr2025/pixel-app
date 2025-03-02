<?php

namespace PixelApp\Http\Controllers\UserAccountControllers;

use PixelApp\Http\Controllers\PixelBaseController as Controller;
use PixelApp\Http\Resources\SingleResource; 
use Illuminate\Support\Facades\Response;
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Models\UsersModule\Signature;
use PixelApp\Services\UserCompanyAccountServices\Signature\SignatureDeletingService;
use PixelApp\Services\UserCompanyAccountServices\Signature\SignatureStoringService;
use PixelApp\Services\UserCompanyAccountServices\Signature\SignatureUpdatingService;
use PixelAppCore\Services\PixelServiceManager;
use Spatie\QueryBuilder\QueryBuilder;

class SignatureController extends Controller
{
    public function index()
    {

        $data = QueryBuilder::for(Signature::class)->get();

        return Response::success(['list' => $data]);
    }

    public function list()
    {
        $data = QueryBuilder::for(Signature::class)->get();
        //
        return Response::json([
            'data' => $data,

        ]);
    }


    public function store()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(SignatureStoringService::class);
        return (new $service())->create();
    }


    public function show()
    {
        $data = Signature::where('user_id',auth()->user()->id)->firstOrFail();
        $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(SingleResource::class);
        return new $resourceClass($data);
    }
 
    public function update()
    {
        $signature = Signature::where('user_id',auth()->user()->id)->firstOrFail();
        $service = PixelServiceManager::getServiceForServiceBaseType(SignatureUpdatingService::class);
        return (new $service($signature))->update();
    }


    public function destroy( $signature)
    {
        $signature = Signature::findOrFail($signature);
        $service = PixelServiceManager::getServiceForServiceBaseType(SignatureDeletingService::class);
        return (new $service($signature))->delete();
    }
}
