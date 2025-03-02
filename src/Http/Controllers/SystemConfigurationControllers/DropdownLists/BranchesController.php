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
use PixelApp\Http\Resources\SystemConfigurationResources\DropdownLists\Branches\BranchResource;
use PixelApp\Models\SystemConfigurationModels\Branch;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\BranchDeletingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\BranchStoringService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\BranchUpdatingService;
use PixelAppCore\Services\PixelServiceManager;

class BranchesController extends Controller
{
    protected $filterable = [
        'name',
        'status'
    ];

    public function index(Request $request)
    {
        $data = QueryBuilder::for(Branch::class)
            ->allowedFilters($this->filterable)
            ->with(['parent'])
            ->datesFiltering()
            ->customOrdering()
            ->paginate($request->pageSize ?? 10);

        return Response::success(['list' => $data]);
    }
    public function listChildrenBranches()
    {
        $data = QueryBuilder::for(Branch::class)
            ->whereNull('parent_id')
            ->allowedFilters($this->filterable)
            ->datesFiltering()
            ->customOrdering('created_at', 'asc')
            ->first();

        return Response::success( $data->toArray());
    }
    public function show( $branch)
    {
        $branch = Branch::findOrFail($branch);
        $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(SingleResource::class);
        return new $resourceClass($branch);
    }

    function list()
    {
        $total = Branch::query()->active()->count();
        $data = QueryBuilder::for(Branch::class)
            ->scopes("active")
            ->allowedFilters(['name'])
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'name']);
        $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(BranchResource::class);
        return $resourceClass::collection($data);
    }

    function subBranches()
    {
        $data = QueryBuilder::for(Branch::class)->with('parent')
            ->allowedFilters(['name', 'parent_id'])
            ->active()
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'name']);
        $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(BranchResource::class);
        return $resourceClass::collection($data);
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

    /**
     * @param Branch $branch
     * @return JsonResponse
     */
    public function update($branch): JsonResponse
    {
        $branch = Branch::findOrFail($branch);
        $service = PixelServiceManager::getServiceForServiceBaseType(BranchUpdatingService::class);
        return (new $service($branch))->update();
    }

    /**
     * @param Branch $branch
     * @return JsonResponse
     */
    public function destroy( $branch): JsonResponse
    {
        $branch = Branch::findOrFail($branch);
        $service = PixelServiceManager::getServiceForServiceBaseType(BranchDeletingService::class);
        return (new $service($branch))->delete();
    }

    public function import(ImportFile $import)
    {
        $file = $import->file;

        return (new ImportBuilder())
            ->file($file)
            ->map(function ($item) {
                $item = array_change_key_case($item);
                return Branch::create($item);
            })
            ->import();
    }

    public function export()
    {
        $taxes = QueryBuilder::for(Branch::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
        $list  = new SheetCollection([
            "Branches" => ExportBuilder::generator($taxes)
        ]);
        return (new ExportBuilder($request->type))
            ->withSheet($list)
            ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
            ->name('Branches')->build();
    }
    
}
