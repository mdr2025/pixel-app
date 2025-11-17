<?php

namespace PixelApp\Http\Controllers\SystemConfigurationControllers\DropdownLists;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use PixelApp\Helpers\PixelGlobalHelpers;
use PixelApp\Helpers\ResponseHelpers;
use PixelApp\Http\Controllers\PixelBaseController as Controller;
use PixelApp\Http\Requests\SystemConfigurationRequests\Branches\BranchAddTeamRequest;
use PixelApp\Http\Requests\SystemConfigurationRequests\Branches\BranchReadingRequest;
use PixelApp\Http\Resources\SystemConfigurationResources\DropdownLists\Branches\BranchResource;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Branch;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\BranchDeletingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\BranchStoringService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\BranchUpdatingService;
use PixelApp\Services\PixelServiceManager;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\BranchShowService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\BranchesListingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\BranchesIndexingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\BranchService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\ChildrenBranchesListingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\ExpImpServices\ExportingServices\BranchesExportingService;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\ExpImpServices\ImportingFunc\BranchesImporter;
use PixelApp\Services\SystemConfigurationServices\DropdownLists\BranchesOperations\SubBranchesListingService;
use PixelApp\Traits\TransactionLogging;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BranchesController extends Controller
{ 

    protected BranchService $branchService;

    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
    }

    public function index(BranchReadingRequest $request): JsonResponse
    {
        return $this->logOnFailureOnly(
            callback : fn() => Response::success($this->branchService->getBranches()),
            operationName : "Branch Indexing Operation"
        ); 
    }

    public function getFirstParentBranch(BranchReadingRequest $request): JsonResponse
    {
        return $this->logOnFailureOnly(
            callback : fn() => Response::success([$this->branchService->getFirstParentBranch()]),
            operationName : "First Parent Branch Fetching Operation"
        ); 
    }

    public function listBranches(): JsonResponse
    {
        return $this->logOnFailureOnly(
            callback : function()
                       {
                            $total = $this->branchService->getCountActiveBranches();
                            $data = $this->branchService->getListBranches();
                            return Response::successList($total, $data);
                       },
            operationName : "Branches Listing Operation"
        ); 
    }

    public function subBranches() : AnonymousResourceCollection
    {
        return $this->logOnFailureOnly(
            callback : fn() => BranchResource::collection($this->branchService->getSubBranches()),
            operationName : "Sub Branches Listing Operation"
        );
    }

    public function store(): JsonResponse
    {
        return $this->surroundWithTransaction(
            fn(): JsonResponse => $this->branchService->store(),
            'Creating Branch Record',
            ['user_id' => Auth::id(), 'request' => request()->all()]
        );
    }

    public function update(int $branch): JsonResponse
    {
        return $this->surroundWithTransaction(function () use ( $branch) {
            return $this->branchService->update( $branch);
        },
            'Updating Branch',
            ['user_id' => Auth::id(), 'request' => request()->all()]
        );
    }

    public function destroy(int $branch): JsonResponse
    {
        return $this->surroundWithTransaction(
            fn() => $this->branchService->destroy($branch),
            'Deleting Branch Record',
            ['user_id' => Auth::id(), 'request' => request()->all()]
        );
    }

    public function indexBranchTeams(BranchReadingRequest $request): JsonResponse
    {
        return $this->logOnFailureOnly(
            callback : fn() => Response::success($this->branchService->getBranchesTeams()) ,
            operationName : "Branch Teams Indexing Operation"
        );
    }

    public function addTeam(BranchAddTeamRequest $request): JsonResponse
    {
        return $this->surroundWithTransaction(
            fn() => $this->branchService->addTeam(),
            'Adding Team',
            ['user_id' => Auth::id(), 'request' => $request->all()]
        );
    }

    public function import(Request $request): JsonResponse
    {
        return $this->surroundWithTransaction(
            fn() => $this->branchService->import(),
            'Import branch',
            [
                'user_id' => Auth::id(),
                'request' => $request->all(),
            ]
        );
    }

    public function export(): JsonResponse | StreamedResponse
    {
        return $this->logOnFailureOnly(
            callback : fn() => $this->branchService->export(),
            loggingContext: ['type' => 'Branch Export'],
            operationName : "Branches Exporting Operation"
        ); 
    }

    public function downloadFileFormat(BranchReadingRequest $request): mixed
    {
        return $this->logOnFailureOnly(
            callback : fn() => $this->branchService->downloadFileFormat(),
            loggingContext: ['type' => 'Branch Export'],
            operationName : 'Download Branch File Format'
        ); 
    }
}
