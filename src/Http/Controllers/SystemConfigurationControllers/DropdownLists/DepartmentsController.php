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
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Models\SystemConfigurationModels\Department;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations\DepartmentDeletingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations\DepartmentStoringService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations\DepartmentUpdatingService;
use PixelAppCore\Services\PixelServiceManager;
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
        $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(SingleResource::class);
        return new $resourceClass($department);
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
        $service = PixelServiceManager::getServiceForServiceBaseType(DepartmentStoringService::class);
        return (new $service())->create();
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
        $service = PixelServiceManager::getServiceForServiceBaseType(DepartmentUpdatingService::class);
        return (new $service($department))->update();
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
        $service = PixelServiceManager::getServiceForServiceBaseType(DepartmentDeletingService::class);
        return (new $service($department))->delete();
    }

    public function import(ImportExcelRequest $request)
    {
        // BasePolicy::check('create', Department::class);
        // $file = $request->file('file');
        // $rules = [
        //     'Name' => 'required|string|unique:departments,name',
        // ];

        // $columnHeaders =  ['Name'];
        // $needed_columns = ['Name' => 'name']; // Dynamic array of column headers
        // $relationNames = []; // Dynamic array of relation names

        // try {

        //     $this->excelService->import($file, $rules, new Department(), $columnHeaders, $relationNames, $needed_columns);
        //     return response()->json(['message' => "data imported successfully"]);
        // } catch (\Exception $e) {

        //     return response()->json(['message' => $e->getMessage()], 406);
        // }
    }

    public function export()
    {
        // BasePolicy::check('read', Department::class);
        // // Retrieve the data to be exported
        // $columnHeaders = ['Name'];
        // $needed_columns = ['id', 'name']; // Dynamic array of column headers
        // $relationNames = []; // Dynamic array of relation names
        // $data = Department::get($needed_columns);

        // $excelFile = $this->excelService->export($data->toArray(), new Department(), $columnHeaders, $relationNames);

        // return response()->download($excelFile);
    }
    public function downloadFileFormat()
    {
        // BasePolicy::check('create', Department::class);
        // return Excel::download(new DepartmentImportFormate(), 'format.xlsx',  WriteTypeExcel::XLSX, ['File-Name' => 'format_sample.xlsx']);
    } 
}
