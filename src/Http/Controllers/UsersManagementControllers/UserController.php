<?php

namespace PixelApp\Http\Controllers\UsersManagementControllers;

use Exception;
use PixelApp\Http\Controllers\PixelBaseController as Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use PixelApp\Http\Requests\UserManagementRequests\UserReadingRequest;
use PixelApp\Http\Requests\UserManagementRequests\UserShowingRequest;
use PixelApp\Models\PixelModelManager;
use PixelApp\Services\UsersManagement\EmailChangerService\EmailChangerService;
use PixelApp\Services\UsersManagement\StatusChangerServices\UserTypeStatusChangers\UserAccountStatusChanger;
use PixelApp\Services\UsersManagement\UpdatingUserByAdminService\UpdatingUserByAdminService;
use PixelApp\Services\PixelServiceManager;
use PixelApp\Services\UsersManagement\ControllerLevelServices\User\UserService;
use PixelApp\Services\UsersManagement\ExpImpServices\UserTypesExpImpServices\UserTypeExpImpServices\UserTypeExportingService;
use PixelApp\Services\UsersManagement\IndexingServices\UserTypeIndexingService;
use PixelApp\Services\UsersManagement\ListingServices\DefaultUsersListingService;
use PixelApp\Services\UsersManagement\ListingServices\UserTypeListingingService;
use PixelApp\Services\UsersManagement\ShowServices\UserTypeShowService;
use PixelApp\Traits\TransactionLogging;

class UserController extends Controller
{

     use TransactionLogging;

    public function __construct(private UserService $userService)
    {
    }

    public function index(UserReadingRequest $request): JsonResponse
    {
        try
        {
            return response()->json($this->userService->getUsers());

        }catch(Exception $e)
        {
             Log::error(
                        "Failed Fetching Users List , Reason : " . $e->getMessage() ,
                        ['user_id' => auth()->id(), 'request' => request()->all()]
                      );

            return Response::error("Failed Fetching Users List , Reason :" . $e->getMessage());
        
        } 
    }

    public function list(): JsonResponse
    {
        try
        {
            return Response::successList(
                $this->userService->getUsersList()['total'],
                $this->userService->getUsersList()['data']
            );
        }catch(Exception $e)
        {
             Log::error(
                        "Failed Users Listing , Reason : " . $e->getMessage() ,
                        ['user_id' => auth()->id(), 'request' => request()->all()]
                      );

            return Response::error("Failed Users Listing , Reason : " . $e->getMessage());
        
        }  
    }

    public function show(UserShowingRequest $request, int $user): JsonResponse
    {
        try
        {
            return Response::success($this->userService->show($user));

        }catch(Exception $e)
        {
             Log::error(
                        "Failed User Showing , Reason : " . $e->getMessage() ,
                        ['user_id' => auth()->id(), 'request' => request()->all()]
                      );

            return Response::error( "Failed User Showing , Reason : " . $e->getMessage());
        
        }  
    }

    public function getFilteredUsersByBranch()
    {
        try
        {
            return $this->userService->getFilteredUsersByBranch();
        
        }catch(Exception $e)
        {
             Log::error(
                        "Failed User Users Listing by Branch , Reason : " . $e->getMessage() ,
                        ['user_id' => auth()->id(), 'request' => request()->all()]
                      );

            return Response::error("Failed User Users Listing by Branch , Reason :" . $e->getMessage());
        
        }  
    }

    public function getPrimaryBranchAndFilteredBranches(): JsonResponse
    {
        try
        {

            $result = $this->userService->getPrimaryAndFilteredBranches();
            return Response::successList($result['total'], $result['data']);

        }catch(Exception $e)
        {
             Log::error(
                        "Failed Primary Branch and Filtered Branches , Reason : " . $e->getMessage() ,
                        ['user_id' => auth()->id(), 'request' => request()->all()]
                      );

            return Response::error("Failed Primary Branch and Filtered Branches , Reason : " . $e->getMessage());
        
        }   
    }

    public function getPrimaryBranchFromUser(): JsonResponse
    {
        try
        {
            $result = $this->userService->getPrimaryBranchFromUser();
            return Response::successList($result['total'], $result['data']);

        }catch(Exception $e)
        {
             Log::error(
                        "Failed Primary Branch Getting , Reason : " . $e->getMessage() ,
                        ['user_id' => auth()->id(), 'request' => request()->all()]
                      );

            return Response::error("Failed Primary Branch Getting , Reason :" . $e->getMessage());
        
        }   
    }

    public function listDefaultUsers()
    {
        try
        {
            return response()->json($this->userService->listDefaultUsers());

        }catch(Exception $e)
        {
             Log::error(
                        "Failed Default Users Listing, Reason : " . $e->getMessage() ,
                        ['user_id' => auth()->id(), 'request' => request()->all()]
                      );

            return Response::error("Failed Default Users Listing , Reason :" . $e->getMessage());
        
        }   
    }

    public function getAccessibleBranchesAndPrimaryBranchFromUser()
    {
        try
        {
            return response()->json($this->userService->getAccessibleBranchesAndPrimaryBranchFromUser());
          
        }catch(Exception $e)
        {
             Log::error(
                        "Accessible Branches and Primary Branch getting , Reason : " . $e->getMessage() ,
                        ['user_id' => auth()->id(), 'request' => request()->all()]
                      );

            return Response::error("Accessible Branches and Primary Branch getting  , Reason :" . $e->getMessage());
        
        }   
    }

    public function update(Request $request, int $user): JsonResponse
    {
        $user = $this->userService->findUserByIdOrFail($user);

        return $this->surroundWithTransaction(
            fn(): JsonResponse => $this->userService->update($user, $request->input('accessible_branches')),
            'Update User',
            [
                'user_id' => $user->id,
                'request' => $request->all(),
            ]
        );
    }

    public function changeAccountStatus(Request $request, int $user): JsonResponse
    {
         $user = $this->userService->findUserByIdOrFail($user);

        return $this->surroundWithTransaction(
            fn(): JsonResponse => $this->userService->changeAccountStatus($user),
            'Change User Account Status',
            [
                'user_id' => $user->id,
                'request' => $request->all(),
            ]
        );
    }

    public function changeEmail(int $user): JsonResponse
    {
        $user = $this->userService->findUserByIdOrFail($user);

        return $this->surroundWithTransaction(
            fn(): JsonResponse => $this->userService->changeEmail($user),
            'Change User Email',
            [
                'user_id' => $user->id,
                'request' => request()->all(),
            ]
        );
    }

    protected function getUserModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }

//     public function index()
//     {
//         $service = PixelServiceManager::getServiceForServiceBaseType(UserTypeIndexingService::class);
//         return (new $service)->index();

//         // BasePolicy::check('readEmployees', User::class);
//         // $data = QueryBuilder::for( $this->getUserModelClass() )
//         //     ->with(['profile', 'profile.country', 'role', 'department'
//         //     //,'branch']
//         //     ])
//         //     ->allowedFilters($this->filters())
//         //     ->datesFiltering()
//         //     ->activeUsers()
//         //     ->customOrdering('accepted_at', 'desc')
//         //     //->where('role_id', '!=', 1)
//         //     ->paginate($request?->pageSize ?? 10);
//         //     $statistics = (new UsersListStatisticsBuilder())->getStatistics();

//         //     return Response::success(['list' => $data, 'statistics' => $statistics]);
//     }
    
//     public function show($user)
//     {
//         $service = PixelServiceManager::getServiceForServiceBaseType(UserTypeShowService::class);
//         return (new $service($user))->show();

// // //      BasePolicy::check('readEmployees', User::class);
// //         $user = $this->getUserModelClass()::findOrFail($user);
// //         $data = ["item" => $user->only("id", "department_id", "role_id", "status")];
// //         return Response::success($data);
//     }


//     public function list()
//     {
//         $service = PixelServiceManager::getServiceForServiceBaseType(UserTypeListingingService::class);
//         return (new $service)->list();

//         // $data = QueryBuilder::for( $this->getUserModelClass() )
//         //     ->allowedFilters([
//         //         AllowedFilter::callback('name', function (Builder $query, $value) {
//         //             $query->where('first_name', 'LIKE', "%{$value}%")
//         //                 ->orWhere('last_name', 'LIKE', "%{$value}%")
//         //                 ->orWhere('mobile', 'LIKE', "%{$value}%")
//         //                 ->orWhere('email', 'LIKE', "%{$value}%");
//         //         })
//         //     ])
//         //     ->with(["profile:user_id,logo"])
//         //     ->activeUsers()
//         //     ->customOrdering('created_at', 'desc')
//         //     ->select("id", "name")
//         //     ->get();
//         // $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(UsersListResource::class);
         
//         // return response()->json([
//         //     "data" => $resourceClass::collection($data)
//         // ]);
//     }

//     public function listDefaultUsers()
//     {
//         $service = PixelServiceManager::getServiceForServiceBaseType(DefaultUsersListingService::class);
//         return (new $service)->list();

//         // $data = QueryBuilder::for( $this->getUserModelClass() )
//         //     ->allowedFilters([
//         //         AllowedFilter::callback('name', function (Builder $query, $value) {
//         //             $query->where('first_name', 'LIKE', "%{$value}%")
//         //                 ->orWhere('last_name', 'LIKE', "%{$value}%")
//         //                 ->orWhere('mobile', 'LIKE', "%{$value}%")
//         //                 ->orWhere('email', 'LIKE', "%{$value}%");
//         //         })
//         //     ])
//         //     ->notSuperAdmin()
//         //     ->activeUsers()
//         //     ->with(['profile:user_id,logo'])
//         //     ->customOrdering('created_at', 'desc')
//         //     ->select("id", "name", "email", "hashed_id")
//         //     ->get();

//         // return response()->json([
//         //     "data" => $data
//         // ]);
//     }
 
//     public function update($user): JsonResponse
//     {
//         //        BasePolicy::check('editEmployees', User::class);
//         $user = $this->getUserModelClass()::findOrFail($user);
//         $service = PixelServiceManager::getServiceForServiceBaseType(UpdatingUserByAdminService::class);
//         return (new $service($user))->change();
//     }

//     public function changeAccountStatus( $user): JsonResponse
//     {
//         //        BasePolicy::check('editEmployees', User::class);
//         $user = $this->getUserModelClass()::findOrFail($user);
//         $service = PixelServiceManager::getServiceForServiceBaseType(UserAccountStatusChanger::class);
//         return (new $service($user))->change();
//     }

//     public function changeEmail($user): JsonResponse
//     {
//         //        BasePolicy::check('editEmployees', User::class);
//         $user = $this->getUserModelClass()::findOrFail($user);
//         $service = PixelServiceManager::getServiceForServiceBaseType(EmailChangerService::class);
//         return (new $service($user))->change();
//     }

//     // public function filters(): array
//     // {
//     //     return  [
//     //         AllowedFilter::custom('created_at' , new MultiFilters(['created_at' , 'accepted_at'])),
//     //         "status",
//     //         AllowedFilter::exact("gender", "profile.gender"),
//     //         AllowedFilter::partial("national_id_number", "profile.national_id_number"),
//     //         AllowedFilter::partial("passport_number", "profile.passport_number"),
//     //         AllowedFilter::partial("country", "profile.country.name"),
//     //         AllowedFilter::partial("department", 'department.name'),
//     //         AllowedFilter::partial("role", 'role.name'),
//     //         AllowedFilter::callback('name', function (Builder $query, $value) {
//     //             $query->where('first_name', 'LIKE', "%{$value}%")
//     //                 ->orWhere('last_name', 'LIKE', "%{$value}%")
//     //                 ->orWhere('mobile', 'LIKE', "%{$value}%")
//     //                 ->orWhere('email', 'LIKE', "%{$value}%");
//     //         })
//     //     ];
//     // }

//     public function export(){
//         // BasePolicy::check('readEmployees', User::class);
//         $service = PixelServiceManager::getServiceForServiceBaseType(UserTypeExportingService::class);
//         return (new $service)->basicExport();
//         // $columnHeaders = ['Name', 'Email', 'Mobile', 'Department', 'Role'];
//         // $needed_columns = ['id', 'name', 'email', 'mobile', 'department_id', 'role_id']; // Dynamic array of column headers
//         // $relationNames = ['department' => ['column' => 'name', 'display' => 'Department'], 'role' => ['column' => 'name', 'display' => 'Role']]; // Dynamic array of relation names
//         // $data = User::with(['department', 'role'])->get($needed_columns);

//         // $excelFile = $this->initExcelService()->export($data->toArray(), new User(), $columnHeaders, $relationNames);
//         // return response()->download($excelFile);
//     }
    
}
