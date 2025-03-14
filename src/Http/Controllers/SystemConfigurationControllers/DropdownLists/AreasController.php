<?php

namespace PixelApp\Http\Controllers\SystemConfigurationControllers\DropdownLists;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use PixelApp\Http\Controllers\PixelBaseController as Controller;
use Spatie\QueryBuilder\QueryBuilder;
use PixelApp\Http\Resources\SingleResource;
use Illuminate\Support\Facades\Response;
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Http\Resources\SystemConfigurationResources\DropdownLists\Areas\AreasListResource;
use PixelApp\Http\Resources\SystemConfigurationResources\DropdownLists\Areas\AreasResource;
use PixelApp\Models\SystemConfigurationModels\CountryModule\Area;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\AreasOperations\AreaDeletingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\AreasOperations\AreaStoringService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\AreasOperations\AreaUpdatingService;
use PixelApp\Services\PixelServiceManager;
use Rap2hpoutre\FastExcel\SheetCollection; 

class AreasController extends Controller
{
    public function index(Request $request)
    {
        $data = QueryBuilder::for(Area::class)
            ->with(['city', 'city.country'])
            ->allowedFilters(['name', 'city.name', 'city.country.name'])
            ->datesFiltering()
            ->customOrdering()
            ->paginate($request->pageSize ?? 10);

        $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(AreasResource::class);
        return Response::success(['list' => new $resourceClass($data)]);
    }

    public function show($area)
    {
        $area = Area::findOrFail($area);
        $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(SingleResource::class);
        return new $resourceClass($area);
    }

    function list()
    {
        $data = QueryBuilder::for(Area::class)
            ->allowedFilters(['name', 'city_id'])
            ->active()
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'name', 'city_id']);
        
        $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(AreasListResource::class);
        return $resourceClass::collection($data);
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function store()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(AreaStoringService::class);
        return (new $service())->create();
    }

    /**
     * @param Area $area
     * @return JsonResponse
     */
    public function update( $area): JsonResponse
    {
        $area = Area::findOrFail($area);
        $service = PixelServiceManager::getServiceForServiceBaseType(AreaUpdatingService::class);
        return (new $service($area))->update();
    }

    /**
     * @param Area $area
     * @return JsonResponse
     */
    public function destroy($area): JsonResponse
    {
        $area = Area::findOrFail($area);
        $service = PixelServiceManager::getServiceForServiceBaseType(AreaDeletingService::class);
        return (new $service($area))->delete();
    }

    public function import()
    {
        $file = $import->file;

        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return Area::create($item);
            })
            ->import();
    }

    public function export()
    {
        $taxes = QueryBuilder::for(Area::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Areas" => ExportBuilder::generator($taxes)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('Areas')->build();
    }
}
