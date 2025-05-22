<?php

namespace PixelApp\Http\Controllers\SystemConfigurationControllers\DropdownLists;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use PixelApp\Http\Controllers\PixelBaseController;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Currency;
use PixelApp\Services\PixelServiceManager;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\CurrenciesOperations\CurrencyUpdatingService;
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

    public function index(Request $request)
    {
        $modelClass = $this->getCurrencyModelClass();
        $data = QueryBuilder::for( $modelClass )
                            ->allowedFilters($this->filterable)
                            ->datesFiltering()->customOrdering()
                            ->paginate($request->pageSize ?? 10);

        return Response::success(['list' => $data]);
    }

    function list()
    {
        $modelClass = $this->getCurrencyModelClass();
        
        $data = QueryBuilder::for($modelClass)
                            ->allowedFilters(['name', 'symbol'])
                            ->active()
                            ->customOrdering('created_at', 'desc')
                            ->get(['id', 'name', 'symbol']);
        
        $total = $modelClass::active()->count();

        return Response::successList($total ,$data);
    }

    protected function findOrFailById(int $id) : Currency
    {
        $modelClass = PixelModelManager::getModelForModelBaseType(Currency::class);
        return $modelClass::findOrFail($id);
    }

    public function update( int $id): JsonResponse
    {
        $currency = $this->findOrFailById($id);
        return (new CurrencyUpdatingService($currency))->update();
    }

    public function importableFormalDownload() 
    {
        // $importer = PixelServiceManager::getServiceForServiceBaseType(CurrenciesImporter::class);
        // return (new $importer())->downloadFormat();
    }

    public function importCurrencies(Request $import)
    {
        // $file = $import->file;

        // return (new ImportBuilder())
        //     ->file($file)
        //     ->map(function ($item) {
        //         $item = array_change_key_case($item);
        //         return Currency::create($item);
        //     })
        //     ->import();
    }

    public function exportCurrencies(Request $request)
    {
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
        return (new CurrencyUpdatingService($currency))->update();
    }
}
