<?php

namespace PixelApp\Http\Controllers\UsersManagementControllers;

use PixelApp\Http\Controllers\PixelBaseController as Controller;
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use PixelApp\Filters\MultiFilters;
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Http\Resources\UserManagementResources\UsersListResource;
use PixelApp\Models\PixelModelManager;
use PixelApp\Services\UsersManagement\EmailChangerService\EmailChangerService;
use PixelApp\Services\UsersManagement\Statistics\UsersList\UsersListStatisticsBuilder;
use PixelApp\Services\UsersManagement\StatusChangerServices\UserTypeStatusChangers\UserAccountStatusChanger;
use PixelApp\Services\UsersManagement\UpdatingUserByAdminService\UpdatingUserByAdminService;
use PixelApp\Services\PixelServiceManager;
use PixelApp\Services\UsersManagement\ExpImpServices\UserTypesExpImpServices\UserTypeExpImpServices\UserTypeExportingService;
use PixelApp\Services\UsersManagement\IndexingServices\UserTypeIndexingService;
use PixelApp\Services\UsersManagement\ListingServices\DefaultUsersListingService;
use PixelApp\Services\UsersManagement\ListingServices\UserTypeListingingService;
use PixelApp\Services\UsersManagement\ShowServices\UserTypeShowService;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    protected function getUserModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }

    public function index()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(UserTypeIndexingService::class);
        return (new $service)->index();

        // BasePolicy::check('readEmployees', User::class);
        // $data = QueryBuilder::for( $this->getUserModelClass() )
        //     ->with(['profile', 'profile.country', 'role', 'department'
        //     //,'branch']
        //     ])
        //     ->allowedFilters($this->filters())
        //     ->datesFiltering()
        //     ->activeUsers()
        //     ->customOrdering('accepted_at', 'desc')
        //     //->where('role_id', '!=', 1)
        //     ->paginate($request?->pageSize ?? 10);
        //     $statistics = (new UsersListStatisticsBuilder())->getStatistics();

        //     return Response::success(['list' => $data, 'statistics' => $statistics]);
    }
    
    public function show($user)
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(UserTypeShowService::class);
        return (new $service($user))->show();

// //      BasePolicy::check('readEmployees', User::class);
//         $user = $this->getUserModelClass()::findOrFail($user);
//         $data = ["item" => $user->only("id", "department_id", "role_id", "status")];
//         return Response::success($data);
    }


    public function list()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(UserTypeListingingService::class);
        return (new $service)->list();

        // $data = QueryBuilder::for( $this->getUserModelClass() )
        //     ->allowedFilters([
        //         AllowedFilter::callback('name', function (Builder $query, $value) {
        //             $query->where('first_name', 'LIKE', "%{$value}%")
        //                 ->orWhere('last_name', 'LIKE', "%{$value}%")
        //                 ->orWhere('mobile', 'LIKE', "%{$value}%")
        //                 ->orWhere('email', 'LIKE', "%{$value}%");
        //         })
        //     ])
        //     ->with(["profile:user_id,logo"])
        //     ->activeUsers()
        //     ->customOrdering('created_at', 'desc')
        //     ->select("id", "name")
        //     ->get();
        // $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(UsersListResource::class);
         
        // return response()->json([
        //     "data" => $resourceClass::collection($data)
        // ]);
    }

    public function listDefaultUsers()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(DefaultUsersListingService::class);
        return (new $service)->list();

        // $data = QueryBuilder::for( $this->getUserModelClass() )
        //     ->allowedFilters([
        //         AllowedFilter::callback('name', function (Builder $query, $value) {
        //             $query->where('first_name', 'LIKE', "%{$value}%")
        //                 ->orWhere('last_name', 'LIKE', "%{$value}%")
        //                 ->orWhere('mobile', 'LIKE', "%{$value}%")
        //                 ->orWhere('email', 'LIKE', "%{$value}%");
        //         })
        //     ])
        //     ->notSuperAdmin()
        //     ->activeUsers()
        //     ->with(['profile:user_id,logo'])
        //     ->customOrdering('created_at', 'desc')
        //     ->select("id", "name", "email", "hashed_id")
        //     ->get();

        // return response()->json([
        //     "data" => $data
        // ]);
    }
 
    public function update($user): JsonResponse
    {
        //        BasePolicy::check('editEmployees', User::class);
        $user = $this->getUserModelClass()::findOrFail($user);
        $service = PixelServiceManager::getServiceForServiceBaseType(UpdatingUserByAdminService::class);
        return (new $service($user))->change();
    }

    public function changeAccountStatus( $user): JsonResponse
    {
        //        BasePolicy::check('editEmployees', User::class);
        $user = $this->getUserModelClass()::findOrFail($user);
        $service = PixelServiceManager::getServiceForServiceBaseType(UserAccountStatusChanger::class);
        return (new $service($user))->change();
    }

    public function changeEmail($user): JsonResponse
    {
        //        BasePolicy::check('editEmployees', User::class);
        $user = $this->getUserModelClass()::findOrFail($user);
        $service = PixelServiceManager::getServiceForServiceBaseType(EmailChangerService::class);
        return (new $service($user))->change();
    }

    // public function filters(): array
    // {
    //     return  [
    //         AllowedFilter::custom('created_at' , new MultiFilters(['created_at' , 'accepted_at'])),
    //         "status",
    //         AllowedFilter::exact("gender", "profile.gender"),
    //         AllowedFilter::partial("national_id_number", "profile.national_id_number"),
    //         AllowedFilter::partial("passport_number", "profile.passport_number"),
    //         AllowedFilter::partial("country", "profile.country.name"),
    //         AllowedFilter::partial("department", 'department.name'),
    //         AllowedFilter::partial("role", 'role.name'),
    //         AllowedFilter::callback('name', function (Builder $query, $value) {
    //             $query->where('first_name', 'LIKE', "%{$value}%")
    //                 ->orWhere('last_name', 'LIKE', "%{$value}%")
    //                 ->orWhere('mobile', 'LIKE', "%{$value}%")
    //                 ->orWhere('email', 'LIKE', "%{$value}%");
    //         })
    //     ];
    // }

    public function export(){
        // BasePolicy::check('readEmployees', User::class);
        $service = PixelServiceManager::getServiceForServiceBaseType(UserTypeExportingService::class);
        return (new $service)->basicExport();
        // $columnHeaders = ['Name', 'Email', 'Mobile', 'Department', 'Role'];
        // $needed_columns = ['id', 'name', 'email', 'mobile', 'department_id', 'role_id']; // Dynamic array of column headers
        // $relationNames = ['department' => ['column' => 'name', 'display' => 'Department'], 'role' => ['column' => 'name', 'display' => 'Role']]; // Dynamic array of relation names
        // $data = User::with(['department', 'role'])->get($needed_columns);

        // $excelFile = $this->initExcelService()->export($data->toArray(), new User(), $columnHeaders, $relationNames);
        // return response()->download($excelFile);
    }
    
}
