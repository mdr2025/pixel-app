<?php

namespace PixelApp\Http\Controllers\SystemConfigurationControllers\DropdownLists;


use PixelApp\Http\Controllers\PixelBaseController as Controller;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\CountryModule\City;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\CitiesServices\CitiesDeletingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\CitiesServices\CitiesStoringService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\CitiesServices\CitiesUpdatingService;
use PixelApp\Services\PixelServiceManager;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\CitiesServices\CitiesIndexingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\CitiesServices\CitiesListingService;

class CitiesController extends Controller
{

    function index()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(CitiesIndexingService::class);
        return (new $service)->index();

        // $data = QueryBuilder::for(City::class)
        //     ->with('country')
        //     ->allowedFilters(['name', 'country_id'])
        //     ->customOrdering('id', 'asc')
        //     ->paginate(request()->pageSize ?? 10);
        // return Response::success(['list' => $data]);
    }

    function list()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(CitiesListingService::class);
        return (new $service)->list();

        // $data = QueryBuilder::for(City::class)
        //     ->allowedFilters(['name', 'country_id'])
        //     ->customOrdering('id', 'asc')
        //     ->get(['id', 'name', 'country_id']);
        // $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(CityResource::class);
        // return $resourceClass::collection($data); 
    }

    public function store()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(CitiesStoringService::class);
        return (new $service())->create();
    }

    
    protected function findOrFailById(int $id) : City
    {
        $modelClass = PixelModelManager::getModelForModelBaseType(City::class);
        return $modelClass::findOrFail($id);
    }

    public function update( $id)
    {
        $city = $this->findOrFailById($id);
        $service = PixelServiceManager::getServiceForServiceBaseType(CitiesUpdatingService::class);
        return (new $service($city))->update();
    }

    public function destroy($id)
    {
        $city = $this->findOrFailById($id);
        $service = PixelServiceManager::getServiceForServiceBaseType(CitiesDeletingService::class);
        return (new $service($city))->delete();
    }
}
