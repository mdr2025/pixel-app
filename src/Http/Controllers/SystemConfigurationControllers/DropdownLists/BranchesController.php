<?php

namespace PixelApp\Http\Controllers\SystemConfigurationControllers\DropdownLists;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
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

     use TransactionLogging;

    protected BranchService $branchService;

    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
    }

    public function index(BranchReadingRequest $request): JsonResponse
    {
        try
        {
            return Response::success($this->branchService->getBranches());

        }catch(Exception $e)
        {
            Log::error(
                        "Failed Fetching Branches , Reason : " . $e->getMessage() ,
                        ['user_id' => auth()->id(), 'request' => request()->all()]
                      );
                      
            return Response::error('Failed Fetching Branches ' . $e->getMessage());
        }
    }

    public function getFirstParentBranch(BranchReadingRequest $request): JsonResponse
    {
        try
        {
           return Response::success([$this->branchService->getFirstParentBranch()]);

        }catch(Exception $e)
        {
            Log::error(
                        "Failed Fetching Branches , Reason : " . $e->getMessage() ,
                        ['user_id' => auth()->id(), 'request' => request()->all()]
                      );

            return Response::error('Failied Fetching Branches ' . $e->getMessage());
        }
    }

    public function listBranches(): JsonResponse
    {
        try
        {
            $total = $this->branchService->getCountActiveBranches();
            $data = $this->branchService->getListBranches();
            return Response::successList($total, $data);

        }catch(Exception $e)
        {
            Log::error(
                        "Failed Listing Branches , Reason : " . $e->getMessage() ,
                        ['user_id' => auth()->id(), 'request' => request()->all()]
                      );

            return Response::error('Failied Listing Branches ' . $e->getMessage());
        } 
    }

    public function subBranches() : AnonymousResourceCollection
    {
        try
        {
            return BranchResource::collection($this->branchService->getSubBranches());

        }catch(Exception $e)
        {
            Log::error(
                        "Failed Listing Sub Branches , Reason : " . $e->getMessage() ,
                        ['user_id' => auth()->id(), 'request' => request()->all()]
                      );

            return Response::error('Failied Sub Listing Branches ' . $e->getMessage());
        } 
    }

    public function store(): JsonResponse
    {
        return $this->surroundWithTransaction(
            fn(): JsonResponse => $this->branchService->store(),
            'Creating Branch Record',
            ['user_id' => auth()->id(), 'request' => request()->all()]
        );
    }

    public function update(int $branch): JsonResponse
    {
        return $this->surroundWithTransaction(function () use ( $branch) {
            return $this->branchService->update( $branch);
        },
            'Updating Branch',
            ['user_id' => auth()->id(), 'request' => request()->all()]
        );
    }

    public function destroy(int $branch): JsonResponse
    {
        return $this->surroundWithTransaction(
            fn() => $this->branchService->destroy($branch),
            'Deleting Branch Record',
            ['user_id' => auth()->id(), 'request' => request()->all()]
        );
    }

    public function indexBranchTeams(BranchReadingRequest $request): JsonResponse
    {
        try
        { 
            return Response::success($this->branchService->getBranchesTeams());
        
        }catch(Exception $e)
        {
            Log::error(
                        "Failed Fetching Branches , Reason : " . $e->getMessage() ,
                        ['user_id' => auth()->id(), 'request' => request()->all()]
                      );

            return Response::error('Failied Fetching Branches ' . $e->getMessage());
        }
    }

    public function addTeam(BranchAddTeamRequest $request): JsonResponse
    {
        return $this->surroundWithTransaction(
            fn() => $this->branchService->addTeam(),
            'Adding Team',
            ['user_id' => auth()->id(), 'request' => $request->all()]
        );
    }

    public function import(Request $request): JsonResponse
    {
        return $this->surroundWithTransaction(
            fn() => $this->branchService->import(),
            'Import branch',
            [
                'user_id' => auth()->id(),
                'request' => $request->all(),
            ]
        );
    }

    public function export(): JsonResponse | StreamedResponse
    {
        return $this->surroundWithTransaction(
            fn() => $this->branchService->export(),
            'Export branch',
            [
                'user_id' => auth()->id(),
                'type' => 'Branch Export',
                'request' => request()->all(),
            ]
        );
    }

    public function downloadFileFormat(BranchReadingRequest $request): mixed
    {
        return $this->surroundWithTransaction(
            fn() => $this->branchService->downloadFileFormat(),
            'Download Branch File Format',
            [
                'user_id' => auth()->id(),
                'request' => $request->all(),
            ]
        );
    }


    // protected $filterable = [
    //     'name',
    //     'status'
    // ];

    // public function index(Request $request)
    // {
    //     $service = PixelServiceManager::getServiceForServiceBaseType(BranchesIndexingService::class);
    //     return (new $service)->index();

    //     // $data = QueryBuilder::for(Branch::class)
    //     //     ->allowedFilters($this->filterable)
    //     //     ->with(['parent'])
    //     //     ->datesFiltering()
    //     //     ->customOrdering()
    //     //     ->paginate($request->pageSize ?? 10);

    //     // return Response::success(['list' => $data]);
    // }
    
    // function list()
    // {
    //     $service = PixelServiceManager::getServiceForServiceBaseType(BranchesListingService::class);
    //     return (new $service)->list();


    //     // $total = Branch::query()->active()->count();
    //     // $data = QueryBuilder::for(Branch::class)
    //     //     ->scopes("active")
    //     //     ->allowedFilters(['name'])
    //     //     ->customOrdering('created_at', 'desc')
    //     //     ->get(['id', 'name']);
    //     // $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(BranchResource::class);
    //     // return $resourceClass::collection($data);
    // }

    // public function listChildrenBranches()
    // {
    //     $service = PixelServiceManager::getServiceForServiceBaseType(ChildrenBranchesListingService::class);
    //     return (new $service)->list();

    //     // $data = QueryBuilder::for(Branch::class)
    //     //     ->whereNull('parent_id')
    //     //     ->allowedFilters($this->filterable)
    //     //     ->datesFiltering()
    //     //     ->customOrdering('created_at', 'asc')
    //     //     ->first();

    //     // return Response::success( $data->toArray());
    // }

    // function subBranches()
    // {
    //     $service = PixelServiceManager::getServiceForServiceBaseType(SubBranchesListingService::class);
    //     return (new $service)->list();

    //     // $data = QueryBuilder::for(Branch::class)->with('parent')
    //     //     ->allowedFilters(['name', 'parent_id'])
    //     //     ->active()
    //     //     ->customOrdering('created_at', 'desc')
    //     //     ->get(['id', 'name']);
    //     // $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(BranchResource::class);
    //     // return $resourceClass::collection($data);
    // }

    // public function show( $branch)
    // {
    //     $service = PixelServiceManager::getServiceForServiceBaseType(BranchShowService::class);
    //     return (new $service($branch))->show();

    //     // $branch = Branch::findOrFail($branch);
    //     // $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(SingleResource::class);
    //     // return new $resourceClass($branch);
    // }
    // /**
    //  * @param Request $request
    //  * @return JsonResponse
    //  * @throws Exception
    //  */
    // public function store()
    // {
    //     $service = PixelServiceManager::getServiceForServiceBaseType(BranchStoringService::class);
    //     return (new $service())->create();
    // }

    // protected function findOrFailById(int $id) : Branch
    // {
    //     $modelClass = PixelModelManager::getModelForModelBaseType(Branch::class);
    //     return $modelClass::findOrFail($id);
    // }
    // /**
    //  * @param int $branch
    //  * @return JsonResponse
    //  */
    // public function update($branch): JsonResponse
    // {
    //     $branch = $this->findOrFailById($branch);
    //     $service = PixelServiceManager::getServiceForServiceBaseType(BranchUpdatingService::class);
    //     return (new $service($branch))->update();
    // }

    // /**
    //  * @param int $branch
    //  * @return JsonResponse
    //  */
    // public function destroy( $branch): JsonResponse
    // {
    //     $branch = $this->findOrFailById($branch);
    //     $service = PixelServiceManager::getServiceForServiceBaseType(BranchDeletingService::class);
    //     return (new $service($branch))->delete();
    // }

    // public function importableFormalDownload() 
    // {
    //     $importer = PixelServiceManager::getServiceForServiceBaseType(BranchesImporter::class);
    //     return (new $importer())->downloadFormat();
    // }

    // public function import()
    // {
    //     $importer = PixelServiceManager::getServiceForServiceBaseType(BranchesImporter::class);
    //     return (new $importer())->import();

    //     // $file = $import->file;

    //     // return (new ImportBuilder())
    //     //     ->file($file)
    //     //     ->map(function ($item) {
    //     //         $item = array_change_key_case($item);
    //     //         return Branch::create($item);
    //     //     })
    //     //     ->import();
    // }

    // public function export()
    // {
    //     $service = PixelServiceManager::getServiceForServiceBaseType(BranchesExportingService::class);
    //     return (new $service())->basicExport("branches");

    //     // $taxes = QueryBuilder::for(Branch::class)->allowedFilters($this->filterable)->datesFiltering()->customOrdering()->cursor();
    //     // $list  = new SheetCollection([
    //     //     "Branches" => ExportBuilder::generator($taxes)
    //     // ]);
    //     // return (new ExportBuilder($request->type))
    //     //     ->withSheet($list)
    //     //     ->map(fn ($item) => ['No.' => $item['id'], 'Name' => $item['name'], 'Status' => $item['status']['label']])
    //     //     ->name('Branches')->build();
    // }
    
}
