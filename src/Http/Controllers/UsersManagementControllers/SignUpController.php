<?php

namespace PixelApp\Http\Controllers\UsersManagementControllers; 

use PixelApp\Http\Controllers\PixelBaseController as Controller;
use PixelApp\Models\WorkSector\UsersModule\User;
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use PixelApp\Filters\MultiFilters;
use PixelApp\Http\Requests\UserManagementRequests\SignupUserApprovingRequest;
use PixelApp\Http\Requests\UserManagementRequests\SignupUserReadingRequest;
use PixelApp\Http\Requests\UserManagementRequests\SignupUserShowingRequest;
use PixelApp\Models\PixelModelManager;
use PixelApp\Services\AuthenticationServices\UserAuthServices\EmailVerificationServices\UserVerificationNotificationResendingService;
use PixelApp\Services\UsersManagement\EmailChangerService\EmailChangerService;
use PixelApp\Services\UsersManagement\Statistics\SignupList\SignupUserStatisticsBuilder;
use PixelApp\Services\UsersManagement\StatusChangerServices\UserTypeStatusChangers\SignUpAccountStatusChanger;
use PixelApp\Services\PixelServiceManager;
use PixelApp\Services\UsersManagement\ExpImpServices\UserTypesExpImpServices\SignUpUsersExpImpServices\SignUpUsersExportingService;
use PixelApp\Services\UsersManagement\IndexingServices\SignUpUsersIndexingService;
use PixelApp\Services\UsersManagement\ShowServices\SignUpUserShowService;
use PixelApp\Services\UsersManagement\StatusChangerServices\UserTypeStatusChangers\SignUpUserStatusChangerServices\SignUpAccountApprovingService;
use PixelApp\Services\UsersManagement\StatusChangerServices\UserTypeStatusChangers\SignUpUserStatusChangerServices\SignUpAccountRejectingService;
use PixelApp\Traits\TransactionLogging;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use PixelApp\Services\UsersManagement\ControllerLevelServices\SignUp\SignUpService;
use PixelApp\Services\UsersManagement\ExpImpServices\UserTypesExpImpServices\SignUpUsersExpImpServices\SignUpUsersCSVExporter;

class SignUpController extends Controller
{
     use TransactionLogging;

    /**
     * @var SignUpService
     */
    public function __construct(private SignUpService $signUpService)
    {
    }

    /**
     * Display a list of sign up users.
     *
     * @param SignupUserReadingRequest $request
     * @return JsonResponse
     */
    public function index(SignupUserReadingRequest $request): JsonResponse
    {
        try
        { 
            return response()->json($this->signUpService->getSignUpList());
        
        }catch(Exception $e)
        {
            Log::error(
                        "Failed Fetching SignUp users , Reason : " . $e->getMessage() ,
                        ['user_id' => auth()->id(), 'request' => request()->all()]
                      );

            return Response::error('Failed Fetching SignUp users , ' . $e->getMessage());
        }
 
    }

    /**
     * Show a specific sign up user with its relations.
     *
     * @param SignupUserShowingRequest $request
     * @param int $user
     *
     * @return JsonResponse
     */
    public function show(SignupUserShowingRequest $request, int $user): JsonResponse
    {
        try
        { 
            return Response::success($this->signUpService->show($user));
        
        }catch(Exception $e)
        {
            Log::error(
                        "Failed Fetching A Signup User , Reason : " . $e->getMessage() ,
                        ['user_id' => auth()->id(), 'request' => request()->all()]
                      );

            return Response::error('Failed Fetching A Signup User ' . $e->getMessage());
        }
    }

    /**
     * Change the account email of a sign up user.
     *
     * @param Request $request
     * @param int $user
     *
     * @return JsonResponse
     */
    public function changeAccountEmail(Request $request, int $user): JsonResponse
    {
        return $this->surroundWithTransaction(
            fn(): JsonResponse => $this->signUpService->changeAccountEmail($user),
            'Sign Up Change Email',
            [
                'user_id' => auth()->id(),
                'request' => $request->all()
            ]
        );
    }

    /**
     * Resend the verification token to the user's email.
     *
     * @return JsonResponse
     */
    public function resendVerificationTokenToUserEmail(): JsonResponse
    {
        return $this->surroundWithTransaction(
            fn(): JsonResponse => $this->signUpService->resendVerificationTokenToUserEmail(),
            'Sign Up Resend Verification Token To User Email',
            [
                'user_id' => auth()->id(),
            ]
        );
    }

    /**
     * Re-verify the email of a sign up user.
     *
     * @param int $user
     *
     * @return JsonResponse
     */
    public function reVerifyEmail(int $user): JsonResponse
    {
        return $this->surroundWithTransaction(
            fn(): JsonResponse => $this->signUpService->reVerifyEmail($user),
            'Sign Up Re Verify Email',
            [
                'user_id' => auth()->id(),
            ]
        );
    }

    /**
     * Approve the account of a sign up user.
     *
     * @param Request $request
     * @param int $user
     *
     * @return JsonResponse
     */
    public function approveAccount(Request $request, int $user): JsonResponse
    {
        return $this->surroundWithTransaction(
            fn(): JsonResponse => $this->signUpService->approveAccount($user),
            'Sign Up Approve Account',
            [
                'user_id' => auth()->id(),
                'request' => $request->all()
            ]
        );
    }

    /**
     * Reject the account of a sign up user.
     *
     * @param SignupUserRejectRequest $request
     * @param int $user
     *
     * @return JsonResponse
     */
    public function rejectAccount(Request $request, int $user): JsonResponse
    {
        return $this->surroundWithTransaction(
            fn(): JsonResponse => $this->signUpService->rejectAccount($user),
            'Sign Up Reject Account',
            [
                'user_id' => auth()->id(),
                'request' => $request->all()
            ]
        );
    }

    protected function getUserModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }

//     public function index()
//     {
//         $service = PixelServiceManager::getServiceForServiceBaseType(SignUpUsersIndexingService::class);
//         return (new $service)->index();

// //        BasePolicy::check('readSignUpList', User::class);

//         // $data = QueryBuilder::for( $this->getUserModelClass() )
//         //     ->with(['profile', 'profile.country', 'department'])
//         //     ->allowedFilters($this->filters())
//         //     ->signup()
//         //     ->datesFiltering()
//         //     ->customOrdering()
//         //     ->when($request->has('filter.email_verified_at'), function ($query) use ($request) {
//         //         if ($request->input('filter.email_verified_at') == 'verified') {
//         //             return $query->whereNotNull('email_verified_at');
//         //         } elseif ($request->input('filter.email_verified_at') == 'not verified') {
//         //             return $query->whereNull('email_verified_at');
//         //         }
//         //     })
//         //     ->paginate($request?->pageSize ?? 10);

//         //  $statistics = (new SignupUserStatisticsBuilder())->getStatistics();


//         // return Response::success(['list' => $data, 'statistics' => $statistics]);
//     }

//     public function show($user): string
//     {
//         $service = PixelServiceManager::getServiceForServiceBaseType(SignUpUserShowService::class);
//         return (new $service($user))->show();

// //        BasePolicy::check('readSignUpList', User::class);
//         // $user = $this->getUserModelClass()::findOrFail($user);
//         // return Response::success(["item" => $user->only("id" , "email")]);
//     }

//     public function changeAccountEmail( $user) : JsonResponse
//     {
//         //        BasePolicy::check('editSignUpUsers', User::class);
//         $user = $this->getUserModelClass()::findOrFail($user);
//         $service = PixelServiceManager::getServiceForServiceBaseType(EmailChangerService::class);
//         return (new $service($user))->change();
//     }

//     /**
//      * @return JsonResponse
//      * reVerify user by email post data
//      */
//     public function resendVerificationTokenToUserEmail(): JsonResponse
//     {
//         $service = PixelServiceManager::getServiceForServiceBaseType(UserVerificationNotificationResendingService::class);
//         return (new $service())->resend();
//     }

//     /**
//      * @param User $user
//      * @return JsonResponse
//      * reVerify user by user id route param
//      */
//     public function reVerifyEmail($user): JsonResponse
//     {
//         $user = $this->getUserModelClass()::findOrFail($user);
//         $service = PixelServiceManager::getServiceForServiceBaseType(UserVerificationNotificationResendingService::class);
//         return (new $service())->setAuthenticatable($user)->resend();
//     }

//     public function approveAccount($user) : JsonResponse
//     { 
// //        BasePolicy::check('editSignUpUsers', User::class);
//         $user = $this->getUserModelClass()::findOrFail($user);
//         $service = PixelServiceManager::getServiceForServiceBaseType(SignUpAccountApprovingService::class);
//         return (new $service($user))->approve();
//     }

//     public function rejectAccount(int $user) : JsonResponse
//     {
//         //        BasePolicy::check('editSignUpUsers', User::class);
//         $user = $this->getUserModelClass()::findOrFail($user);
//         $service = PixelServiceManager::getServiceForServiceBaseType(SignUpAccountRejectingService::class);
//         return (new $service($user))->reject();
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
//     //         AllowedFilter::callback('name', function (Builder $query, $value) {
//     //             $query->where('first_name', 'LIKE', "%{$value}%")
//     //                 ->orWhere('last_name', 'LIKE', "%{$value}%")
//     //                 ->orWhere('mobile', 'LIKE', "%{$value}%")
//     //                 ->orWhere('email', 'LIKE', "%{$value}%");
//     //         })
//     //     ];
//     // }

 
    public function export(SignupUserReadingRequest $request)
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(SignUpUsersCSVExporter::class);
        return (new $service)->export("signup-list");
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
