<?php

namespace PixelApp\Http\Controllers\SystemConfigurationControllers\DropdownLists;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use PixelApp\Http\Controllers\PixelBaseController as Controller;
use PixelApp\Http\Requests\SystemConfigurationRequests\Departments\DepartmentDownloadFileFormateRequest;
use PixelApp\Http\Requests\SystemConfigurationRequests\Departments\DepartmentReadingRequest;
use PixelApp\Http\Requests\SystemConfigurationRequests\Departments\DepartmentSupervisorsRequest;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Department;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations\DepartmentDeletingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations\DepartmentStoringService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations\DepartmentUpdatingService;
use PixelApp\Services\PixelServiceManager;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations\DepartmentService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations\DepartmentShowService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations\DepartmentsIndexingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations\DepartmentsListingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations\ExpImpServices\ExportingServices\DepartmentsExportingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\DepartmentsOperations\ExpImpServices\ImportingFunc\DepartmentsImporter;
use PixelApp\Traits\TransactionLogging;
use Rap2hpoutre\FastExcel\SheetCollection;

class DepartmentsController extends Controller
{
    use TransactionLogging;

    protected DepartmentService $departmentService;

    public function __construct(DepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
    }

    public function index(DepartmentReadingRequest $request): JsonResponse
    {
        try
        {
            return Response::success($this->departmentService->getDepartmentsGroupByBranch());
        }catch(Exception $e)
        {
            Log::error(
                            'Failed To Index Departments List , Reason : ' . $e->getMessage(),
                            ['user_id' => auth()->id(), 'request' => $request->all()]
                      );
            
            return Response::error($e->getMessage());
        }
    }

    public function list(Request $request): JsonResponse
    {
        try
        {
            $total = $this->departmentService->getCountActiveDepartments();
            $data = $this->departmentService->getListDepartments();

            return Response::successList($total, $data);

        }catch(Exception $e)
        {
            Log::error(
                            'Failed To List Departments , Reason : ' . $e->getMessage(),
                            ['user_id' => auth()->id(), 'request' => $request->all()]
                      );

            return Response::error($e->getMessage());
        }
    }

    public function store(): JsonResponse
    {
        return $this->surroundWithTransaction(
            fn(): JsonResponse => $this->departmentService->store(),
            'Creating Department Record',
            ['user_id' => auth()->id(), 'request' => request()->all()]
        );
    }

    public function update(int $department): JsonResponse
    {
        return $this->surroundWithTransaction(
            fn(): JsonResponse => $this->departmentService->update($department),
            'Department Update',
            ['user_id' => auth()->id(), 'request' => request()->all()]
        );
    }

    public function destroy(int $department): JsonResponse
    {
        return $this->surroundWithTransaction(
            fn() => $this->departmentService->destroy($department),
            'Deleting Department Record',
            ['user_id' => auth()->id(), 'request' => request()->all()]
        );
    }

    public function assignSupervisors(DepartmentSupervisorsRequest $request, int $department): JsonResponse
    {
        $result = $this->departmentService->validateToAssignSupervisors($request, $department);

        if (!$result)
        {
            return Response::error([], ['Supervisors assignment failed']);
        }

        return Response::success([], ['Supervisors assigned successfully']);
    }

    public function import(DepartmentImportingRequest $request): JsonResponse
    {
        return $this->surroundWithTransaction(
            fn() => $this->departmentService->import(),
            'Import departments',
            [
                'user_id' => auth()->id(),
                'request' => $request->all(),
            ]
        );
    }

    public function export(): JsonResponse
    {
        return $this->departmentService->export();
    }

    public function downloadFileFormat(DepartmentDownloadFileFormateRequest $request): mixed
    {
        return $this->departmentService->downloadFileFormat();
    }

//     public function index()
//     { 
//         $service = PixelServiceManager::getServiceForServiceBaseType(DepartmentsIndexingService::class);
//         return (new $service)->index();

//         // BasePolicy::check('read', Department::class);
//         // $data = QueryBuilder::for(Department::class)->with('parent')
//         //     ->allowedFilters(["name", "status"])
//         //     ->datesFiltering()->customOrdering()
//         //     ->paginate($request?->pageSize ?? 10);

//         // return Response::success(['list' => $data]);
//     }

//     public function show($department)
//     {
//         $service = PixelServiceManager::getServiceForServiceBaseType(DepartmentShowService::class);
//         return (new $service($department))->show();

//         // BasePolicy::check('read', Department::class);
//         // $department = Department::findOrFail($department);
//         // $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(SingleResource::class);
//         // return new $resourceClass($department);
//     }

//     function list()
//     {
//         $service = PixelServiceManager::getServiceForServiceBaseType(DepartmentsListingService::class);
//         return (new $service)->list();


//         // $total = Department::active()->count();

//         // $data = QueryBuilder::for(Department::class)
//         //                     ->with('parent')
//         //                     ->allowedFilters(['name'])
//         //                     ->active()
//         //                     ->customOrdering('created_at', 'desc')
//         //                     ->get(['id', 'name']);
        
            
//         // return Response::successList($total, $data);
//     }

//    /**
//      * @return JsonResponse
//      */
//     public function store(): JsonResponse
//     {
//         $service = PixelServiceManager::getServiceForServiceBaseType(DepartmentStoringService::class);
//         return (new $service())->create();
//     }

//     protected function findOrFailById(int $id) : Department
//     {
//         $modelClass = PixelModelManager::getModelForModelBaseType(Department::class);
//         return $modelClass::findOrFail($id);
//     }

//     /**
//      * @param int $department
//      * @return JsonResponse
//      * @throws JsonException
//      */
//     public function update(int $department): JsonResponse
//     {
//          //need policy here
//         $department = $this->findOrFailById($department);
//         $service = PixelServiceManager::getServiceForServiceBaseType(DepartmentUpdatingService::class);
//         return (new $service($department))->update();
//     }

//    /**
//      * @param int $department
//      * @return JsonResponse
//      * @throws JsonException
//      */
//     public function destroy(int $department): JsonResponse
//     {
//         //need policy here
//         $department = $this->findOrFailById($department);
//         $service = PixelServiceManager::getServiceForServiceBaseType(DepartmentDeletingService::class);
//         return (new $service($department))->delete();
//     }

//     public function importableFormalDownload() 
//     {
//         $importer = PixelServiceManager::getServiceForServiceBaseType(DepartmentsImporter::class);
//         return (new $importer())->downloadFormat();
    
//         // BasePolicy::check('create', Department::class);
//         // return Excel::download(new DepartmentImportFormate(), 'format.xlsx',  WriteTypeExcel::XLSX, ['File-Name' => 'format_sample.xlsx']);
//     } 

//     public function import()
//     {
//         $importer = PixelServiceManager::getServiceForServiceBaseType(DepartmentsImporter::class);
//         return (new $importer())->import();

//         // BasePolicy::check('create', Department::class);
//         // $file = $request->file('file');
//         // $rules = [
//         //     'Name' => 'required|string|unique:departments,name',
//         // ];

//         // $columnHeaders =  ['Name'];
//         // $needed_columns = ['Name' => 'name']; // Dynamic array of column headers
//         // $relationNames = []; // Dynamic array of relation names

//         // try {

//         //     $this->excelService->import($file, $rules, new Department(), $columnHeaders, $relationNames, $needed_columns);
//         //     return response()->json(['message' => "data imported successfully"]);
//         // } catch (\Exception $e) {

//         //     return response()->json(['message' => $e->getMessage()], 406);
//         // }
//     }

//     public function export()
//     {
//         $service = PixelServiceManager::getServiceForServiceBaseType(DepartmentsExportingService::class);
//         return (new $service())->basicExport("departments");

//         // BasePolicy::check('read', Department::class);
//         // // Retrieve the data to be exported
//         // $columnHeaders = ['Name'];
//         // $needed_columns = ['id', 'name']; // Dynamic array of column headers
//         // $relationNames = []; // Dynamic array of relation names
//         // $data = Department::get($needed_columns);

//         // $excelFile = $this->excelService->export($data->toArray(), new Department(), $columnHeaders, $relationNames);

//         // return response()->download($excelFile);
//     }
}
