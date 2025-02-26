<?php

namespace PixelApp\Http\Controllers\SystemConfigurationControllers\DropdownLists;

use Illuminate\Http\Request;
use PixelApp\Http\Controllers\PixelBaseController as Controller;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Response;
use PixelApp\Http\Resources\SystemConfigurationResources\DropdownLists\Cities\CityResource;
use PixelApp\Models\SystemConfigurationModels\CountryModule\City;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\CitiesService\CitiesDeletingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\CitiesService\CitiesStoringService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\CitiesService\CitiesUpdatingService;

class CitiesController extends Controller
{

    function index()
    {
        $data = QueryBuilder::for(City::class)
            ->with('country')
            ->allowedFilters(['name', 'country_id'])
            ->customOrdering('id', 'asc')
            ->paginate(request()->pageSize ?? 10);
        return Response::success(['list' => $data]);
    }
    function list()
    {
        $data = QueryBuilder::for(City::class)
            ->allowedFilters(['name', 'country_id'])
            ->customOrdering('id', 'asc')
            ->get(['id', 'name', 'country_id']);
        return CityResource::collection($data);
    }

    public function store()
    {
        return (new CitiesStoringService())->create();
    }

    public function update( $id)
    {
        $city = City::findOrFail($id);
        return (new CitiesUpdatingService($city))->update();
    }

    public function destroy($id)
    {
        $city = City::findOrFail($id);
        return (new CitiesDeletingService($city))->delete();
    }
}
