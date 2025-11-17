<?php

namespace PixelApp\Http\Controllers\UsersManagementControllers; 

use PixelApp\Http\Controllers\PixelBaseController as Controller;
use PixelApp\Models\WorkSector\UsersModule\User;
use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        return $this->logOnFailureOnly(
                    callback : fn() => $this->signUpService->getSignUpList(),
                    operationName : "SignUp Users Indexing Operation"
                ); 
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
        return $this->logOnFailureOnly(
                    callback : fn() => Response::success($this->signUpService->show($user)),
                    operationName : "Fetching A Signup User Operation"
                );
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
                'user_id' => Auth::id(),
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
                        'user_id' => Auth::id(),
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
                        'user_id' => Auth::id(),
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
                'user_id' => Auth::id(),
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
                'user_id' => Auth::id(),
                'request' => $request->all()
            ]
        );
    }

    public function export(SignupUserReadingRequest $request)
    {
        return $this->logOnFailureOnly(
                    callback : function()
                    {
                        return Response::success($this->signUpService->export());
                    },
                    operationName : "Signup Users List Exporting Operation"
                );
    }
    
    protected function getUserModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }
 
}
