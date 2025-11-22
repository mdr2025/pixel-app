<?php

namespace PixelApp\Http\Controllers\SystemConfigurationControllers\DropdownLists;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
use Symfony\Component\HttpFoundation\StreamedResponse;

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
        return $this->logOnFailureOnly(
                    callback : fn()  => Response::success($this->departmentService->getDepartmentsGroupByBranch()),
                    operationName : "Departments Indexing Operation"
                );
    }

    public function list(Request $request): JsonResponse
    {
        return $this->logOnFailureOnly(
                    callback : function() 
                    {
                        $total = $this->departmentService->getCountActiveDepartments();
                        $data = $this->departmentService->getListDepartments();

                        return Response::successList($total, $data); 
                    },
                    operationName : "Depatments Listing Operation"
                );
    }

    public function store(): JsonResponse
    {
        return $this->surroundWithTransaction(
                    fn(): JsonResponse => $this->departmentService->store(),
                    'Creating Department Record',
                    ['user_id' => Auth::id(), 'request' => request()->all()]
                );
    }

    public function update(int $department): JsonResponse
    {
        return $this->surroundWithTransaction(
                    fn(): JsonResponse => $this->departmentService->update($department),
                    'Department Update',
                    ['user_id' => Auth::id(), 'request' => request()->all()]
                );
    }

    public function destroy(int $department): JsonResponse
    {
        return $this->surroundWithTransaction(
                    fn() => $this->departmentService->destroy($department),
                    'Deleting Department Record',
                    ['user_id' => Auth::id(), 'request' => request()->all()]
                );
    }
 
    public function import(Request $request): JsonResponse
    {
        return $this->surroundWithTransaction(
                    fn() => $this->departmentService->import(),
                    'Import departments',
                    [
                        'user_id' => Auth::id(),
                        'request' => $request->all(),
                    ]
                );
    }

    public function export(): JsonResponse | StreamedResponse
    {
        return $this->logOnFailureOnly(
                    callback : fn()  => $this->departmentService->export(),
                    operationName : "Fetching A Signup User Operation"
                );
    }

    public function downloadFileFormat(DepartmentDownloadFileFormateRequest $request): mixed
    {
        return $this->logOnFailureOnly(
                    callback : fn()  => $this->departmentService->downloadFileFormat(),
                    operationName : "Fetching A Signup User Operation"
                );
    }
}
