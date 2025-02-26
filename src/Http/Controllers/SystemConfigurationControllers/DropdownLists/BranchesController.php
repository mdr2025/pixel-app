<?php

namespace PixelApp\Http\Controllers\SystemConfigurationControllers\DropdownLists;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use PixelApp\Http\Controllers\PixelBaseController as Controller;
use Spatie\QueryBuilder\QueryBuilder;
use PixelApp\Http\Resources\SingleResource;
use Illuminate\Support\Facades\Response;
use PixelApp\Http\Resources\SystemConfigurationResources\DropdownLists\Branches\BranchResource;
use PixelApp\Models\SystemConfigurationModels\Branch;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\BranchDeletingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\BranchStoringService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\BranchUpdatingService;

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
            ->datesFiltering()
            ->customOrdering()
            ->paginate($request->pageSize ?? 10);

        return Response::success(['list' => $data]);
    }

    public function show(Branch $branch)
    {
        return new SingleResource($branch);
    }

    function list()
    {
        $data = QueryBuilder::for(Branch::class)
            ->scopes("active")
            ->allowedFilters(['name'])
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'name']);
        return BranchResource::collection($data);
    }

    function subBranches()
    {
        $data = QueryBuilder::for(Branch::class)->with('parent')
            ->allowedFilters(['name', 'parent_id'])
            ->active()
            ->customOrdering('created_at', 'desc')
            ->get(['id', 'name']);
        return BranchResource::collection($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     */
    public function store()
    {
        return (new BranchStoringService())->create();
    }

    /**
     * @param Branch $branch
     * @return JsonResponse
     */
    public function update(Branch $branch): JsonResponse
    {
        return (new BranchUpdatingService($branch))->update();
    }

    /**
     * @param Branch $branch
     * @return JsonResponse
     */
    public function destroy(Branch $branch): JsonResponse
    {
        return (new BranchDeletingService($branch))->delete();
    }

    public function import(){}

    public function export(){}
    
}
