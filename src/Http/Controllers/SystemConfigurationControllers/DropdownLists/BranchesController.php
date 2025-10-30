<?php

namespace PixelApp\Http\Controllers\SystemConfigurationControllers\DropdownLists;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use PixelApp\Http\Controllers\PixelBaseController as Controller;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Branch;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\BranchDeletingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\BranchStoringService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\BranchUpdatingService;
use PixelApp\Services\PixelServiceManager;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\BranchShowService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\BranchesListingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\BranchesIndexingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\ChildrenBranchesListingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\ExpImpServices\ExportingServices\BranchesExportingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\ExpImpServices\ImportingFunc\BranchesImporter;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\SubBranchesListingService;

class BranchesController extends Controller
{
    protected $filterable = [
        'name',
        'status'
    ];

    public function index(Request $request)
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(BranchesIndexingService::class);
        return (new $service)->index();

        // $data = QueryBuilder::for(Branch::class)
        //     ->allowedFilters($this->filterable)
        //     ->with(['parent'])
        //     ->datesFiltering()
        //     ->customOrdering()
        //     ->paginate($request->pageSize ?? 10);

        // return Response::success(['list' => $data]);
    }
    
    function list()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(BranchesListingService::class);
        return (new $service)->list();


        // $total = Branch::query()->active()->count();
        // $data = QueryBuilder::for(Branch::class)
        //     ->scopes("active")
        //     ->allowedFilters(['name'])
        //     ->customOrdering('created_at', 'desc')
        //     ->get(['id', 'name']);
        // $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(BranchResource::class);
        // return $resourceClass::collection($data);
    }

    public function listChildrenBranches()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(ChildrenBranchesListingService::class);
        return (new $service)->list();

        // $data = QueryBuilder::for(Branch::class)
        //     ->whereNull('parent_id')
        //     ->allowedFilters($this->filterable)
        //     ->datesFiltering()
        //     ->customOrdering('created_at', 'asc')
        //     ->first();

        // return Response::success( $data->toArray());
    }

    function subBranches()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(SubBranchesListingService::class);
        return (new $service)->list();

        // $data = QueryBuilder::for(Branch::class)->with('parent')
        //     ->allowedFilters(['name', 'parent_id'])
        //     ->active()
        //     ->customOrdering('created_at', 'desc')
        //     ->get(['id', 'name']);
        // $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(BranchResource::class);
        // return $resourceClass::collection($data);
    }

    public function show( $branch)
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(BranchShowService::class);
        return (new $service($branch))->show();

        // $branch = Branch::findOrFail($branch);
        // $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(SingleResource::class);
        // return new $resourceClass($branch);
    }
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(BranchStoringService::class);
        return (new $service())->create();
    }

    protected function findOrFailById(int $id) : Branch
    {
        $modelClass = PixelModelManager::getModelForModelBaseType(Branch::class);
        return $modelClass::findOrFail($id);
    }
    /**
     * @param int $branch
     * @return JsonResponse
     */
    public function update($branch): JsonResponse
    {
        $branch = $this->findOrFailById($branch);
        $service = PixelServiceManager::getServiceForServiceBaseType(BranchUpdatingService::class);
        return (new $service($branch))->update();
    }

    /**
     * @param int $branch
     * @return JsonResponse
     */
    public function destroy( $branch): JsonResponse
    {
        $branch = $this->findOrFailById($branch);
        $service = PixelServiceManager::getServiceForServiceBaseType(BranchDeletingService::class);
        return (new $service($branch))->delete();
    }

    public function importableFormalDownload() 
    {
        $importer = PixelServiceManager::getServiceForServiceBaseType(BranchesImporter::class);
        return (new $importer())->downloadFormat();
    }

    public function import()
    {
        $importer = PixelServiceManager::getServiceForServiceBaseType(BranchesImporter::class);
        return (new $importer())->import();

        // $file = $import->file;

        // return (new ImportBuilder())
        //     ->file($file)
        //     ->map(function ($item) {
        //         $item = array_change_key_case($item);
        //         return Branch::create($item);
        //     })
        //     ->import();
    }

    public function export()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(BranchesExportingService::class);
        return (new $service())->basicExport("branches");

        // $taxes = QueryBuilder::for(Branch::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        // $list  = new SheetCollection([
        //     "Branches" => ExportBuilder::generator($taxes)
        // ]);
        // return (new ExportBuilder($request->type))
        //     ->withSheet($list)
        //     ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
        //     ->name('Branches')->build();
    }
    
}
