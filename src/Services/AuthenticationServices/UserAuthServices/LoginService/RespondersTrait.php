<?php


namespace PixelApp\Services\AuthenticationServices\UserAuthServices\LoginService;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use PixelApp\Http\Resources\UserManagementResources\ModelResources\UserResource;
use PixelApp\Services\UserEncapsulatedFunc\UserTokensHandlers\UserTokensGenerator;
use PixelApp\Services\UserEncapsulatedFunc\UserTokensHandlers\UserTokensRevoker;

trait RespondersTrait
{

    private array $responseData = [];

    private function initUserAccessTokensRevoker()  :UserTokensRevoker
    {
        return new UserTokensRevoker();
    }

    private function revokeUserPreviousTokens(): void
    {
        $this->initUserAccessTokensRevoker()->AddUserAccessTokensToRevoke( $this->user )->revoke();
    }

    private function initUserTokensGenerator()  :UserTokensGenerator
    {
        return new UserTokensGenerator( $this->user );
    }

   /**
     * @return array
     * @throws Exception
     */
    protected function getResponseData(): array
    {
        return array_merge(
            (new UserResource($this->user->load(["role:id,name", "role.permissions:name,id"])))->toArray(request()),
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
