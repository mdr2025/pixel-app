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
use PixelApp\Services\UsersManagement\ExpImpServices\UserTypesExpImpServices\UserTypeExpImpServices\UserTypeCSVExporter;
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
        return $this->logOnFailureOnly(
                    callback : fn()  => response()->json($this->userService->getUsers()),
                    operationName : "Fetching Users List Operation"
                );
    }

    public function list(): JsonResponse
    {
        return $this->logOnFailureOnly(
                    callback : function()
                    {
                        $total = $this->userService->getUsersList()['total'];
                        $data = $this->userService->getUsersList()['data'];
                        return Response::successList($total , $data);
                    },
                    operationName :  "Listing Users List Operation"
                );
    }

    public function show(UserShowingRequest $request, int $user): JsonResponse
    {
        return $this->logOnFailureOnly(
                    callback : fn()  => Response::success($this->userService->show($user)),
                    operationName : "User Showing Operation"
                );
    }

    public function getFilteredUsersByBranch()
    {
        return $this->logOnFailureOnly(
                    callback : fn()  => $this->userService->getFilteredUsersByBranch(),
                    operationName : "Branch Filtered Users Getting Operation"
                );
    }

    public function getPrimaryBranchAndFilteredBranches(): JsonResponse
    {
        return $this->logOnFailureOnly(
                    callback : function()
                    {
                        $result = $this->userService->getPrimaryAndFilteredBranches();
                        return Response::successList($result['total'], $result['data']);
                    },
                    operationName : "User Primary Branch and Filtered Branes Getting Operation"
                );   
    }

    public function getPrimaryBranchFromUser(): JsonResponse
    {
        return $this->logOnFailureOnly(
                    callback : function()
                    {
                        $result = $this->userService->getPrimaryBranchFromUser();
                        return Response::successList($result['total'], $result['data']);
                    },
                    operationName : "User Primary Branch Getting Operation"
                );
    }

    public function listDefaultUsers()
    {
        return $this->logOnFailureOnly(
                    callback : fn()  => Response::success($this->userService->listDefaultUsers()),
                    operationName : "Default Users Listing Operation"
                );
    }

    public function getAccessibleBranchesAndPrimaryBranchFromUser()
    {
        return $this->logOnFailureOnly(
                    callback : fn()  => Response::success($this->userService->getAccessibleBranchesAndPrimaryBranchFromUser()),
                    operationName : "Accessible Branches and Primary Branch getting Operation"
                ); 
    }

    public function update(Request $request, int $user): JsonResponse
    {
        $user = $this->userService->findUserByIdOrFail($user);
        $accessible_branches = $request->input('accessible_branches' , []) ;

        return $this->surroundWithTransaction(
            fn() =>  $this->userService->update($user, $accessible_branches),
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

    public function export(UserReadingRequest $request)
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(UserTypeCSVExporter::class);
        return (new $service)->export("users-list");
    }
    
}
