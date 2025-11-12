<?php

namespace PixelApp\Http\Controllers\SystemConfigurationControllers\DropdownLists;

use Exception;
use Illuminate\Http\JsonResponse;
use PixelApp\Http\Controllers\PixelBaseController as Controller;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\CountryModule\Area;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\AreasOperations\AreaDeletingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\AreasOperations\AreaStoringService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\AreasOperations\AreaUpdatingService;
use PixelApp\Services\PixelServiceManager;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\AreasOperations\AreaShowService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\AreasOperations\AreasIndexingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\AreasOperations\AreasListingingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\AreasOperations\ExpImpServices\AreasExportingServices\AreaExportingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\AreasOperations\ExpImpServices\AreasImportingFunc\AreasImporter;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AreasController extends Controller
{
    public function index()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(AreasIndexingService::class);
        return (new $service)->index();
    }

    public function show($area)
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(AreaShowService::class);
        return (new $service($area))->show();
    }

    function list()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(AreasListingingService::class);
        return (new $service)->list();


        // $data = QueryBuilder::for(Area::class)
        //     ->allowedFilters(['name', 'city_id'])
        //     ->active()
        //     ->customOrdering('created_at', 'desc')
        //     ->get(['id', 'name', 'city_id']);
        
        // $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(AreasListResource::class);
        // return $resourceClass::collection($data);
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

    protected function findOrFailById(int $id) : Area
    {
        $modelClass = PixelModelManager::getModelForModelBaseType(Area::class);
        return $modelClass::findOrFail($id);
    }

    /**
     * @param int $area
     * @return JsonResponse
     */
    public function update(int $area): JsonResponse
    {
        $area = $this->findOrFailById($area);
        $service = PixelServiceManager::getServiceForServiceBaseType(AreaUpdatingService::class);
        return (new $service($area))->update();
    }

    /**
     * @param int $area
     * @return JsonResponse
     */
    public function destroy(int $area): JsonResponse
    {
        $area = $this->findOrFailById($area);
        $service = PixelServiceManager::getServiceForServiceBaseType(AreaDeletingService::class);
        return (new $service($area))->delete();
    }
 
    public function importableFormalDownload() 
    {
        $importer = PixelServiceManager::getServiceForServiceBaseType(AreasImporter::class);
        return (new $importer())->downloadFormat();
    }

    public function import()
    {
        $importer = PixelServiceManager::getServiceForServiceBaseType(AreasImporter::class);
        return (new $importer())->import();
        // $file = $import->file;

        // return (new ImportBuilder())
        //     ->file($file)
        //     ->map(function ($item) {
        //         $item = array_change_key_case($item);
        //         return Area::create($item);
        //     })
        //     ->import();
    }

    public function export(): JsonResponse | StreamedResponse
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(AreaExportingService::class);
        return (new $service())->basicExport("areas");

        // $taxes = QueryBuilder::for(Area::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        // $list  = new SheetCollection([
        //     "Areas" => ExportBuilder::generator($taxes)
        // ]);
        // return (new ExportBuilder($request->type))
        //     ->withSheet($list)
        //     ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
        //     ->name('Areas')->build();
    }
}
