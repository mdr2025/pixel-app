<?php

namespace PixelApp\Services\AuthenticationServices\UserAuthServices\LoginService;

use PixelApp\Models\UsersModule\PixelUser;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PixelApp\Exceptions\JsonException;
use PixelApp\Http\Requests\AuthenticationRequests\UserAuthenticationRequests\LoginRequest;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Models\PixelModelManager;
use PixelApp\Services\Traits\GeneralValidationMethods;

class LoginService
{
    protected ?PixelUser $user = null;
    protected array $data = [];

    use RespondersTrait , GeneralValidationMethods;


    protected function getRequestFormClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(LoginRequest::class);
    }

    /**
     * @return $this
     * @throws JsonException
     */
    private function checkAccountStatus(): self
    {
        $status = $this->user->status;

        if ($status != 'active') {
            $errorMessage = match ($status) {
                "pending"  => "Login Failed , your account is pending",
                "inactive" => "Login Failed , Your account is inactive ",
                "rejected" => "Login Failed , your account is blocked",
            };
            throw new JsonException($errorMessage, 422);
        }
        return $this;
    }

    /**
     * @return $this
     * @throws JsonException
     */
    private function checkVerificationStatus(): self
    {
        if (!$this->user->isVerified())
        {
            throw new JsonException("Login Failed , Your Email Is Not Verified Yet !");
        }
        return $this;
    }

    /**
     * @return $this
     * @throws JsonException
     */
    private function checkApprovementStatus(): self
    {
        if (!$this->user->accepted_at)
        {
            throw new JsonException("Login Failed , Your Account Is Not Approved Yet !");
        }
        return $this;
    }
    private function checkUserPassword(PixelUser $user ): bool
    {
        return Hash::check($this->data["password"], $user->password);
    }

    // $extraLoginConditions must be like :
    // [ ["column" => "id" , "operator" => "=" , "value" => "value" ] ]
    /**
     * @param Builder $queryBuilder
     * @param array $extraLoginConditions
     * @return Builder
     */
    private function addExtraLoginConditionsToBuilder(Builder $queryBuilder, array $extraLoginConditions): Builder
    {
        /**
         * @TODO Make this array an array of DataResourceInstructor elements
         */
        foreach ($extraLoginConditions as $condition) {
            $queryBuilder->where($condition["column"], $condition["operator"] ?? "=", $condition["value"]);
        }
        return $queryBuilder;
    }

    protected function getUserModelClass()  :string
    {
        return PixelModelManager::getUserModelClass();
    }
    // $extraLoginConditions must be like :
    // ["column1" => "value1" , "column2" => "value2"]
    /**
     * @param array $extraLoginConditions
     * @throws Exception
     * @return $this
     */
    private function setValidUser( array $extraLoginConditions = []): self
    {
        $userBuilder = $this->getUserModelClass()::where("email", $this->data["email"]);

        /**  @var PixelUser $user */
        $user = $this->addExtraLoginConditionsToBuilder($userBuilder, $extraLoginConditions)->first();
        if ($user  &&  $this->checkUserPassword($user))
        {
            $this->user = $user;
            return $this;
        }
        throw new JsonException("Login failed , Password or email are incorrect!");
    }

    /**
     * @param array $extraLoginConditions
     * @return JsonResponse
     */
    public function login( array $extraLoginConditions = []): JsonResponse
    {
        try {
            $this->initValidator()->validateRequest()->setRequestData();

            $this->setValidUser( $extraLoginConditions)
                ->checkVerificationStatus()
                ->checkApprovementStatus()
                 ->checkAccountStatus();

            DB::beginTransaction();

            $response = $this->getSuccessResponse(['User Logged in Successfully'], 200);

            DB::commit();

            return $response;

        } catch (Exception $e)
        {
            DB::rollBack();
            return $this->getErrorResponse([$e->getMessage()]);
        }
    }
}
