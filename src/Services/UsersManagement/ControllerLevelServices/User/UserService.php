<?php

namespace PixelApp\Services\UsersManagement\ControllerLevelServices\User;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use PixelApp\Http\Resources\UserManagementResources\UsersListResource;
use PixelApp\Models\ModelConfigs\UsersModule\User\UserConfig;
use PixelApp\Models\UsersModule\PixelUser;
use PixelApp\Services\PixelServiceManager;
use PixelApp\Services\Repositories\SystemSettings\UsersModule\User\UserRepository;
use PixelApp\Services\UsersManagement\EmailChangerService\UserTypesEmailChangerServices\UserTypeEmailChangerService;
use PixelApp\Services\UsersManagement\Statistics\UsersList\UsersListStatisticsBuilder;
use PixelApp\Services\UsersManagement\StatusChangerServices\UserTypeStatusChangers\UserAccountStatusChanger;
use PixelApp\Services\UsersManagement\UpdatingUserByAdminService\UpdatingUserByAdminService;

class UserService
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    protected function initUserAuthorizationService()   : UserAuthorizationService
    {
        return app(UserAuthorizationService::class);
    }
    /**
     * Get the users list.
     *
     * @return array
     */
    public function getUsers(): array
    {
        return [
            'list' => $this->userRepository->getUsers(
                UserConfig::getFilters(),
                UserConfig::getRelations()
            ),
            'statistics' => $this->getStatistics(),
            'permissions' => $this->initUserAuthorizationService()->getPermissions()
        ];
    }

    /**
     * Get the users list.
     *
     * @return array
     */
    public function getUsersList(): array
    {
        $data = $this->userRepository->getUsersList(
            UserConfig::getUsersListFilters(),
            UserConfig::getUsersListRelations()
        );

        $total = $this->userRepository->getTotalActiveUsers();

        // Transform data using UsersListResource
        $transformedData = UsersListResource::collection($data)->resolve();

        return [
            'data' => $transformedData,
            'total' => $total
        ];
    }

    public function findUserByIdOrFail(int $userId) : PixelUser
    {
        return $this->userRepository->findUserByIdOrFail($userId);
    }

    /**
     * Show a specific user with its relations.
     *
     * @param int $user
     * @return array
     */
    public function show(int $user): array
    {
        $user = $this->findUserByIdOrFail($user);

        return [
            'item' => array_merge(
                $user->only(
                    'id',
                    'department_id',
                    'role_id',
                    'status',
                    'branch_id',
                ),
                [
                    'accessible_branches' => $user->accessibleBranches()->pluck('id')->toArray(),
                ]
            )
        ];
    }

    /**
     * Get the users list.
     *
     * @return JsonResponse
     */
    public function getFilteredUsersByBranch(): JsonResponse
    {
        $data = $this->userRepository->filterUsersByBranch(
            UserConfig::getFilterUsersByBranch(),
            UserConfig::getUsersListRelations()
        );

        $total = $this->userRepository->getTotalActiveUsers();

        return Response::successList($total, $data);
    }

    /**
     * Get the primary and filtered branches.
     *
     * @param array $filteredBranchIds
     * @return array
     */
    public function getPrimaryAndFilteredBranches(): array
    {
        $user = Auth::user();

        $primaryBranch = $this->userRepository->getPrimaryBranchFromUser($user);
        $accessibleBranches = $this->userRepository->getAccessibleBranchesIdsFromUser($user);

        // allowed only
        $filteredBranches = array_intersect($this->getFilteredBranchesIds(), $accessibleBranches);

        // remove primary branch if exists in filtered
        if (in_array($primaryBranch->id, $filteredBranches)) {
            $filteredBranches = array_diff($filteredBranches, [$primaryBranch->id]);
        }

        $filteredBranches = $this->userRepository->getBranchesByIds($filteredBranches);

        $data = array_merge([$primaryBranch->toArray()], $filteredBranches);
        $total = count($data);

        return [
            'data' => $data,
            'total' => $total
        ];
    }

    /**
     * Get the primary branch from the user.
     *
     * @return array
     */
    public function getPrimaryBranchFromUser(): array
    {
        $user = Auth::user();

        $primaryBranch = $this->userRepository->getPrimaryBranchFromUser($user);

        $total = $primaryBranch ? 1 : 0;
        $data = $primaryBranch ? [$primaryBranch->toArray()] : [];

        return [
            'data' => $data,
            'total' => $total
        ];
    }

    /**
     * List default users.
     *
     * @return array
     */
    public function listDefaultUser(): array
    {
        $data = $this->userRepository->listDefaultUser(UserConfig::getDefaultUserFilters());

        return [
            'data' => $data,
            'total' => count($data)
        ];
    }

    /**
     * Get the accessible branches and primary branch from the user.
     *
     * @return array
     */
    public function getAccessibleBranchesAndPrimaryBranchFromUser(): array
    {
        $user = Auth::user();
        $accessibleBranches = $this->userRepository->getAccessibleBranchesFromUser($user);
        $primaryBranch = $this->userRepository->getPrimaryBranchFromUser($user);

        return [
            'data' => $accessibleBranches,
            'main_branch' => $primaryBranch
        ];
    }

    /**
     * Update the user.
     *
     * @param PixelUser $user
     * @param array $accessibleBranches
     * @return JsonResponse
     */
    public function update(PixelUser $user, array $accessibleBranches): JsonResponse
    { 
        // Perform the update operation using the service class
        $service = PixelServiceManager::getServiceForServiceBaseType(UpdatingUserByAdminService::class);
        $response =  (new $service($user))->change();

        // Update the user's accessible branches
        if ($accessibleBranches) {
            $this->userRepository->syncAccessibleBranches($user, $accessibleBranches);
        }

        // Return the update response
        return $response;
    }


    /**
     * Change the user's account status.
     *
     * @param PixelUser $user
     * @return JsonResponse
     */
    public function changeAccountStatus(PixelUser $user): JsonResponse
    { 
        $service = PixelServiceManager::getServiceForServiceBaseType(UserAccountStatusChanger::class);
        return (new $service($user))->change();
    }

    public function changeEmail(PixelUser $user): JsonResponse
    { 
        $service = PixelServiceManager::getServiceForServiceBaseType(UserTypeEmailChangerService::class);
        return (new $service($user))->change();
    }

    /**
     * Get the filtered branches ids.
     *
     * @return array
     */
    private function getFilteredBranchesIds(): array
    {
        return request()->input('filtered_branches_ids', []);
    }

    /**
     * Get the statistics for the users list.
     *
     * @return array
     */
    private function getStatistics(): array
    {
        return (new UsersListStatisticsBuilder())->getStatistics();
    }
}
