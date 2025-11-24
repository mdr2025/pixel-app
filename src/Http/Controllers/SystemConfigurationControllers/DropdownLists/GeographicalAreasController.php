<?php

namespace PixelApp\Http\Controllers\SystemConfigurationControllers\DropdownLists;

use Exception;
use Illuminate\Http\JsonResponse;
use PixelApp\Http\Controllers\PixelBaseController as Controller;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\CountryModule\GeographicalArea;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\GeographicalAreasOperations\GeographicalAreaDeletingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\GeographicalAreasOperations\GeographicalAreaStoringService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\GeographicalAreasOperations\GeographicalAreaUpdatingService;
use PixelApp\Services\PixelServiceManager;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\GeographicalAreasOperations\GeographicalAreaShowService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\GeographicalAreasOperations\GeographicalAreasIndexingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\GeographicalAreasOperations\GeographicalAreasListingingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\GeographicalAreasOperations\ExpImpServices\GeographicalAreasExportingServices\GeographicalAreaExportingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\GeographicalAreasOperations\ExpImpServices\GeographicalAreasImportingFunc\GeographicalAreasImporter;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GeographicalAreasController extends Controller
{
    public function index()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(GeographicalAreasIndexingService::class);
        return (new $service)->index();
    }

    public function show($geographical_area)
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(GeographicalAreaShowService::class);
        return (new $service($geographical_area))->show();
    }

    function list()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(GeographicalAreasListingingService::class);
        return (new $service)->list(); 
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    public function store()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(GeographicalAreaStoringService::class);
        return (new $service())->create();
    }

    protected function findOrFailById(int $id) : GeographicalArea
    {
        $modelClass = PixelModelManager::getModelForModelBaseType(GeographicalArea::class);
        return $modelClass::findOrFail($id);
    }

    /**
     * @param int $geographical_area
     * @return JsonResponse
     */
    public function update(int $geographical_area): JsonResponse
    {
        $area = $this->findOrFailById($geographical_area);
        $service = PixelServiceManager::getServiceForServiceBaseType(GeographicalAreaUpdatingService::class);
        return (new $service($area))->update();
    }

    /**
     * @param int $geographical_area
     * @return JsonResponse
     */
    public function destroy(int $geographical_area): JsonResponse
    {
        $area = $this->findOrFailById($geographical_area);
        $service = PixelServiceManager::getServiceForServiceBaseType(GeographicalAreaDeletingService::class);
        return (new $service($area))->delete();
    }
 
    public function importableFormalDownload() 
    {
        $importer = PixelServiceManager::getServiceForServiceBaseType(GeographicalAreasImporter::class);
        return (new $importer())->downloadFormat();
    }

    public function import()
    {
        $importer = PixelServiceManager::getServiceForServiceBaseType(GeographicalAreasImporter::class);
        return (new $importer())->import(); 
    }

    public function export(): JsonResponse | StreamedResponse
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(GeographicalAreaExportingService::class);
        return (new $service())->basicExport("geographical_areas");
    }
}
