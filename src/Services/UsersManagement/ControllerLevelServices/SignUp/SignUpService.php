<?php

namespace PixelApp\Services\UsersManagement\ControllerLevelServices\SignUp;

use PixelApp\Traits\HandlesBranchIds;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use PixelApp\Models\ModelConfigs\UsersModule\SignUp\SignUpConfig;
use PixelApp\Models\UsersModule\PixelUser;
use PixelApp\Services\PixelServiceManager;
use PixelApp\Services\Repositories\SystemSettings\UsersModule\SignUp\SignUpRepository;
use PixelApp\Services\UsersManagement\ControllerLevelServices\User\UserAuthorizationService;
use PixelApp\Services\UsersManagement\EmailChangerService\EmailChangerService;
use PixelApp\Services\AuthenticationServices\UserAuthServices\EmailVerificationServices\UserVerificationNotificationResendingService;
use PixelApp\Services\UsersManagement\EmailChangerService\UserTypesEmailChangerServices\SignupUserEmailChangerService;
use PixelApp\Services\UsersManagement\ExpImpServices\UserTypesExpImpServices\SignUpUsersExpImpServices\SignUpUsersCSVExporter;
use PixelApp\Services\UsersManagement\StatusChangerServices\UserTypeStatusChangers\SignUpUserStatusChangerServices\SignUpAccountApprovingService;
use PixelApp\Services\UsersManagement\StatusChangerServices\UserTypeStatusChangers\SignUpUserStatusChangerServices\SignUpAccountRejectingService;
use PixelApp\Services\UsersManagement\Statistics\SignupList\SignupUserStatisticsBuilder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SignUpService
{
    use HandlesBranchIds;

    private UserAuthorizationService $authService;

    public function __construct(
        private SignUpRepository $signUpRepository
        
    ) {
    }

    protected function initUserAuthorizationService() : UserAuthorizationService
    {
        return app(UserAuthorizationService::class);
    }
    /**
     * Get the sign up list.
     *
     * @return array
     */
    public function getSignUpList(): array
    {
        $filteredBranchIds = $this->getRequestedBranchIdsWithPrimary(Auth::id());

        return [
            'list' => $this->signUpRepository->getSignUpList(
                SignUpConfig::getFilters(),
                SignUpConfig::getRelations(),
                $filteredBranchIds
            ),
            'permissions' => $this->initUserAuthorizationService()->getPermissions(),
            'statistics' => $this->getStatistics(),
        ];
    }

    /**
     * @param int $user
     *
     * @return array
     */
    public function show(int $user): array
    {
        $user = $this->signUpRepository->fetchByIdOrFail($user);

        return [
            'item' => $user->only('id', 'email')
        ];
    }

    /**
     * @param int $user
     *
     * @return JsonResponse
     */
    public function changeAccountEmail(int $user): JsonResponse
    {
        $user = $this->signUpRepository->fetchByIdOrFail($user);
        $service = PixelServiceManager::getServiceForServiceBaseType(SignupUserEmailChangerService::class);
        return (new $service($user))->change();
    }

    /**
     * @return JsonResponse
     */
    public function resendVerificationTokenToUserEmail(): JsonResponse
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(UserVerificationNotificationResendingService::class);
        return (new $service())->resend();
    }

    /**
     * @param User $user
     *
     * @return JsonResponse
     */
    public function reVerifyEmail(int $userId): JsonResponse
    {
        $user = $this->signUpRepository->fetchByIdOrFail($userId);

        $service = PixelServiceManager::getServiceForServiceBaseType(UserVerificationNotificationResendingService::class);
        return (new $service())->setAuthenticatable($user)->resend();
    }

    /**
     * @param User $user
     *
     * @return JsonResponse
     */
    public function approveAccount(int $userId): JsonResponse
    {
        $user = $this->signUpRepository->fetchByIdOrFail($userId);
        $service = PixelServiceManager::getServiceForServiceBaseType(SignUpAccountApprovingService::class);
        return (new $service($user))->approve();
    }


    /**
     * @param User $user
     *
     * @return JsonResponse
     */
    public function rejectAccount(int $userId): JsonResponse
    {
        $user = $this->signUpRepository->fetchByIdOrFail($userId);
        $service = PixelServiceManager::getServiceForServiceBaseType(SignUpAccountRejectingService::class);
        return (new $service($user))->reject();
    }

    public function export() : JsonResponse | StreamedResponse
    {
        $service = PixelServiceManager::getServiceForServiceBaseType(SignUpUsersCSVExporter::class);
        return (new $service)->export("signup-list");
    }
    /**
     * Get the sign up statistics.
     *
     * @return array
     */
    private function getStatistics(): array
    {
        return (new SignupUserStatisticsBuilder())->getStatistics();
    }

}
