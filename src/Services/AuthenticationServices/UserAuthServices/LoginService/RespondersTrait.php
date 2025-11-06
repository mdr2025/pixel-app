<?php


namespace PixelApp\Services\AuthenticationServices\UserAuthServices\LoginService;

use PixelApp\Models\UsersModule\PixelUser;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Http\Resources\UserManagementResources\ModelResources\UserResource;
use PixelApp\Models\Interfaces\OptionalRelationsInterfaces\MustHaveRole;
use PixelApp\Services\UserEncapsulatedFunc\UserTokensHandlers\UserTokensGenerator;
use PixelApp\Services\UserEncapsulatedFunc\UserTokensHandlers\UserTokensRevoker;

/**
 * @property PixelUser $user
 */
trait RespondersTrait
{

    private array $responseData = [];

    private function initUserAccessTokensRevoker()  :UserTokensRevoker
    {
        return new UserTokensRevoker();
    }

    private function revokeUserPreviousTokens(): void
    {
        $this->initUserAccessTokensRevoker()->addToRevokeForNewLogin( $this->user )->revoke();
    }

    private function initUserTokensGenerator()  :UserTokensGenerator
    {
        return new UserTokensGenerator( $this->user );
    }

    protected function loadUserPermissionRelations() : void
    {
        if($this->user instanceof MustHaveRole)
        {
            $this->user->load(["role:id,name", "role.permissions:name,id"]);
        }
    }
   /**
     * @return array
     * @throws Exception
     */
    protected function getResponseData(): array
    {
        $resourceClass = PixelHttpResourceManager::getResourceForResourceBaseType(UserResource::class);
        return array_merge(
            (new $resourceClass($this->user))->toArray(request()),
            $this->initUserTokensGenerator()->generateTokens()
        );
    }
    private function prepareResponse(): void
    {
        $this->revokeUserPreviousTokens();
    }

    /**
     * @throws Exception
     */
    private function getSuccessResponse($message, $statusCode = 201): JsonResponse
    {
        $this->prepareResponse();
        return Response::success(
            $this->getResponseData() ,
            $message,
            $statusCode
        );
    }

    private function getErrorResponse(array $messages = [], $statusCode = 406): JsonResponse
    {
        return Response::error(
                    !empty($messages) ? $messages : "Login Failed !",
                    $statusCode
                );
    }
}
