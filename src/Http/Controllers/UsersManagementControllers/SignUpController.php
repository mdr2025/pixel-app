<?php

namespace PixelApp\Http\Controllers\UsersManagementControllers; 

use PixelApp\Http\Controllers\PixelBaseController as Controller;
use PixelApp\Models\WorkSector\UsersModule\User;
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use PixelApp\Filters\MultiFilters;
use PixelApp\Http\Requests\UserManagementRequests\SignupUserApprovingRequest;
use PixelApp\Models\PixelModelManager;
use PixelApp\Services\AuthenticationServices\UserAuthServices\EmailVerificationServices\UserVerificationNotificationResendingService;
use PixelApp\Services\UsersManagement\EmailChangerService\EmailChangerService;
use PixelApp\Services\UsersManagement\Statistics\SignupList\SignupUserStatisticsBuilder;
use PixelApp\Services\UsersManagement\StatusChangerServices\UserTypeStatusChangers\SignUpAccountStatusChanger;
use PixelApp\Services\PixelServiceManager;
use PixelApp\Services\UsersManagement\ExpImpServices\UserTypesExpImpServices\SignUpUsersExpImpServices\SignUpUsersExportingService;
use PixelApp\Services\UsersManagement\StatusChangerServices\UserTypeStatusChangers\SignUpUserStatusChangerServices\SignUpAccountApprovingService;
use PixelApp\Services\UsersManagement\StatusChangerServices\UserTypeStatusChangers\SignUpUserStatusChangerServices\SignUpAccountRejectingService;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SignUpController extends Controller
{
    protected function getUserModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }

    public function index(Request $request)
    {
//        BasePolicy::check('readSignUpList', User::class);

        $data = QueryBuilder::for( $this->getUserModelClass() )
            ->with(['profile', 'profile.country', 'department'])
            ->allowedFilters($this->filters())
            ->activeSignup()
            ->datesFiltering()
            ->customOrdering()
            ->when($request->has('filter.email_verified_at'), function ($query) use ($request) {
                if ($request->input('filter.email_verified_at') == 'verified') {
                    return $query->whereNotNull('email_verified_at');
                } elseif ($request->input('filter.email_verified_at') == 'not verified') {
                    return $query->whereNull('email_verified_at');
                }
            })
            ->paginate($request?->pageSize ?? 10);

         $statistics = (new SignupUserStatisticsBuilder())->getStatistics();


        return Response::success(['list' => $data, 'statistics' => $statistics]);
    }

    public function show($user): string
    {
//        BasePolicy::check('readSignUpList', User::class);
        $user = $this->getUserModelClass()::findOrFail($user);
        return Response::success(["item" => $user->only("id" , "email")]);
    }

    public function changeAccountEmail( $user) : JsonResponse
    {
        //        BasePolicy::check('editSignUpUsers', User::class);
        $user = $this->getUsverModelClass()::findOrFail($user);
        $service = PixelServiceManager::getServiceForServiceBaseType(EmailChangerService::class);
        return (new $service($user))->change();
    }

    /**
     * @return JsonResponse
     * reVerify user by email post data
     */
    public function resendVerificationTokenToUserEmail(): JsonResponse
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(UserVerificationNotificationResendingService::class);
        return (new $service())->resend();
    }

    /**
     * @param User $user
     * @return JsonResponse
     * reVerify user by user id route param
     */
    public function reVerifyEmail($user): JsonResponse
    {
        $user = $this->getUserModelClass()::findOrFail($user);
        $service = PixelServiceManager::getServiceForServiceBaseType(UserVerificationNotificationResendingService::class);
        return (new $service())->setAuthenticatable($user)->resend();
    }

    public function approveAccount($user) : JsonResponse
    { 
//        BasePolicy::check('editSignUpUsers', User::class);
        $user = $this->getUserModelClass()::findOrFail($user);
        $service = PixelServiceManager::getServiceForServiceBaseType(SignUpAccountApprovingService::class);
        return (new $service($user))->approve();
    }

    public function rejectAccount(int $user) : JsonResponse
    {
        //        BasePolicy::check('editSignUpUsers', User::class);
        $user = $this->getUserModelClass()::findOrFail($user);
        $service = PixelServiceManager::getServiceForServiceBaseType(SignUpAccountRejectingService::class);
        return (new $service($user))->reject();
    }
    
    public function filters(): array
    {
        return  [
            AllowedFilter::custom('created_at' , new MultiFilters(['created_at' , 'accepted_at'])),

            "status",
            AllowedFilter::exact("gender", "profile.gender"),
            AllowedFilter::partial("national_id_number", "profile.national_id_number"),
            AllowedFilter::partial("passport_number", "profile.passport_number"),
            AllowedFilter::partial("country", "profile.country.name"),
            AllowedFilter::callback('name', function (Builder $query, $value) {
                $query->where('first_name', 'LIKE', "%{$value}%")
                    ->orWhere('last_name', 'LIKE', "%{$value}%")
                    ->orWhere('mobile', 'LIKE', "%{$value}%")
                    ->orWhere('email', 'LIKE', "%{$value}%");
            })
        ];
    }

 
    public function export()
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(SignUpUsersExportingService::class);
        return (new $service)->basicExport();
        // BasePolicy::check('readSignUpList', User::class);
        // $columnHeaders = ['First Name', 'Last Name', 'Name', 'Email', 'Mobile'];
        // $needed_columns = ['id', 'first_name', 'last_name', 'name', 'email', 'mobile']; // Dynamic array of column headers
        // $relationNames = ['department' => ['column' => 'name', 'display' => 'Department'], 'role' => ['column' => 'name', 'display' => 'Role']]; // Dynamic array of relation names
        // $relationNames = []; // Dynamic array of relation names
        // $data = User::get($needed_columns);

        // $excelFile = $this->initExcelService()->export($data->toArray(), new User(), $columnHeaders, $relationNames);
        // return response()->download($excelFile);
    }
}
