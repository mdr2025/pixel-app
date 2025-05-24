<?php

namespace PixelApp\Http\Controllers\UserAccountControllers;

use PixelApp\Http\Controllers\PixelBaseController as Controller;
use PixelApp\Http\Resources\SingleResource; 
use Illuminate\Support\Facades\Response;
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\UsersModule\Signature;
use PixelApp\Services\UserCompanyAccountServices\Signature\SignatureDeletingService;
use PixelApp\Services\UserCompanyAccountServices\Signature\SignatureStoringService;
use PixelApp\Services\UserCompanyAccountServices\Signature\SignatureUpdatingService;
use PixelApp\Services\PixelServiceManager;
use Spatie\QueryBuilder\QueryBuilder;

class SignatureController extends Controller
{

    protected function getSignatureModelClass()  :string
    {
        return PixelModelManager::getModelForModelBaseType(Signature::class);
    }

    public function index()
    {
        $modelClass = $this->getSignatureModelClass();

        $data = QueryBuilder::for($modelClass)->get();

        return Response::success(['list' => $data]);
    }

    public function list()
    {
        $modelClass = $this->getSignatureModelClass();

        $data = QueryBuilder::for($modelClass)->get();
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
        $modelClass = $this->getSignatureModelClass();

        $data = $modelClass::where('user_id',auth()->user()->id)->firstOrFail();
        $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(SingleResource::class);
        return new $resourceClass($data);
    }
 
    public function update()
    {
        $modelClass = $this->getSignatureModelClass();

        $signature = $modelClass::where('user_id',auth()->user()->id)->firstOrFail();

        $service = PixelServiceManager::getServiceForServiceBaseType(SignatureUpdatingService::class);
        return (new $service($signature))->update();
    }


    public function destroy( $signature)
    {
        $modelClass = $this->getSignatureModelClass();

        $signature = $modelClass::findOrFail($signature);
        
        $service = PixelServiceManager::getServiceForServiceBaseType(SignatureDeletingService::class);
        return (new $service($signature))->delete();
    }
}
