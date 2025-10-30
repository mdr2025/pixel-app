<?php

namespace PixelApp\Http\Controllers\SystemConfigurationControllers\DropdownLists;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use PixelApp\Http\Controllers\PixelBaseController;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Currency;
use PixelApp\Services\PixelServiceManager;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\CurrenciesOperations\CurrenciesIndexingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\CurrenciesOperations\CurrenciesListingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\CurrenciesOperations\CurrencyUpdatingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\CurrenciesOperations\ExpImpServices\ExportingServices\CurrenciesExportingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\CurrenciesOperations\ExpImpServices\ImportingFunc\CurrenciesImporter;
use Rap2hpoutre\FastExcel\SheetCollection;
use Spatie\QueryBuilder\QueryBuilder;

class CurrenciesController extends PixelBaseController
{

    protected $filterable = [
        'name',
        'status'
    ];
    public function __construct()
    {
        // $this->middleware('permission:read_sc-dropdown-lists')->only(['index']);
        // $this->middleware('permission:create_sc-dropdown-lists')->only(['store']);
        // $this->middleware('permission:read_sc-dropdown-lists')->only(['show']);
        // $this->middleware('permission:edit_sc-dropdown-liste')->only(['update']);
        // $this->middleware('permission:delete_sc-dropdown-lists')->only(['destroy']);
        // $this->middleware('permission:import_sc-dropdown-lists')->only(['importCurrencys']);
        // $this->middleware('permission:export_sc-dropdown-lists')->only(['exportCurrencies']);
    }

    protected function getCurrencyModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Currency::class);
    }

    public function index()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(CurrenciesIndexingService::class);
        return (new $service)->index();
    }

    function list()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(CurrenciesListingService::class);
        return (new $service)->list();
    }

    protected function findOrFailById(int $id) : Currency
    {
        $modelClass = $this->getCurrencyModelClass();
        return $modelClass::findOrFail($id);
    }

    public function update( int $id): JsonResponse
    {
        $currency = $this->findOrFailById($id);
        
        $upadtingService = PixelServiceManager::getServiceForServiceBaseType(CurrencyUpdatingService::class);
    
        return (new $upadtingService($currency))->update();
    }

    public function importableFormalDownload() 
    {
        $importer = PixelServiceManager::getServiceForServiceBaseType(CurrenciesImporter::class);
        return (new $importer())->downloadFormat();
    }

    public function import()
    {
        $importer = PixelServiceManager::getServiceForServiceBaseType(CurrenciesImporter::class);
        return (new $importer())->import();

        // $file = $import->file;

        // return (new ImportBuilder())
        //     ->file($file)
        //     ->map(function ($item) {
        //         $item = array_change_key_case($item);
        //         return Currency::create($item);
        //     })
        //     ->import();
    }

    public function export()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(CurrenciesExportingService::class);
        return (new $service())->basicExport("currencies");

        // $taxes = QueryBuilder::for(Currency::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        // $list  = new SheetCollection([
        //     "Currenciess" => ExportBuilder::generator($taxes)
        // ]);
        // return (new ExportBuilder($request->type))
        //     ->withSheet($list)
        //     ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
        //     ->name('Currencies')->build();
    }

    public function setMainCurrency(Request $request, int $id): JsonResponse
    {
        $request->merge(['is_main' => 1]);
        $currency = $this->findOrFailById($id);
        
        $upadtingService = PixelServiceManager::getServiceForServiceBaseType(CurrencyUpdatingService::class);
    
        return (new $upadtingService($currency))->update();
    }
}
