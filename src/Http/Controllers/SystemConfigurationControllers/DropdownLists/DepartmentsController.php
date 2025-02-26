<?php

namespace PixelApp\Http\Controllers\SystemConfigurationControllers\DropdownLists;

use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use PixelApp\Http\Controllers\PixelBaseController as Controller;
use Spatie\QueryBuilder\QueryBuilder;
use PixelApp\Http\Resources\SingleResource;
use Illuminate\Support\Facades\Response;
use PixelApp\Models\SystemConfigurationModels\Department;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations\DepartmentDeletingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations\DepartmentStoringService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations\DepartmentUpdatingService;
use Rap2hpoutre\FastExcel\SheetCollection;

class DepartmentsController extends Controller
{
    public function index(Request $request)
    {
        BasePolicy::check('read', Department::class);

        $data = QueryBuilder::for(Department::class)->with('parent')
            ->allowedFilters(["name", "status"])
            ->datesFiltering()->customOrdering()
            ->paginate($request?->pageSize ?? 10);

        return Response::success(['list' => $data]);
    }

    public function show($department)
    {
        BasePolicy::check('read', Department::class);
        $department = Department::findOrFail($department);
        return new SingleResource($department);
    }

    function list()
    {
        
        $total = Department::active()->count();

        $data = QueryBuilder::for(Department::class)
                            ->with('parent')
                            ->allowedFilters(['name'])
                            ->active()
                            ->customOrdering('created_at', 'desc')
                            ->get(['id', 'name']);
        
            
        return Response::successList($total, $data);
    }

   /**
     * @return JsonResponse
     */
    public function store(): JsonResponse
    {
        return (new DepartmentStoringService())->create();
    }

    /**
     * @param int $department
     * @return JsonResponse
     * @throws JsonException
     */
    public function update($department): JsonResponse
    {
         //need policy here
        $department = Department::findOrFail($department);
        return (new DepartmentUpdatingService($department))->update();
    }

   /**
     * @param int $department
     * @return JsonResponse
     * @throws JsonException
     */
    public function destroy( $department): JsonResponse
    {
        //need policy here
        $department = Department::findOrFail($department);
        return (new DepartmentDeletingService($department))->delete();
    }

    public function export(){}
    public function import(){}
    public function downloadFileFormat(){}
}
