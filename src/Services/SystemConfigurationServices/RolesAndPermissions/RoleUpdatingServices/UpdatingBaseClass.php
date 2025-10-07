<?php

namespace PixelApp\Services\SystemConfigurationServices\RolesAndPermissions\RoleUpdatingServices;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use PixelApp\Config\ConfigFileIdentifiers\PixelBaseConfigFileIdentifiers\PixelAppACLConfigFileIdentifier;
use PixelApp\Config\ConfigFileIdentifiers\PixelBaseConfigFileIdentifiers\PixelBaseConfigFileIdentifier;
use PixelApp\Config\PixelConfigManager;
use PixelApp\Http\Requests\SystemConfigurationRequests\RoleRequests\RoleUpdatingRequest;
use PixelApp\Models\SystemConfigurationModels\RoleModel;
use ValidatorLib\JSONValidator;
use ValidatorLib\Validator;
use PixelApp\Exceptions\JsonException;
use PixelApp\Http\Requests\PixelHttpRequestManager;

abstract class UpdatingBaseClass
{
    protected Validator $validator;
    protected RoleModel $role;
    protected array $data;
    protected array $DefaultRoles;


 
    /**
     * @throws Exception
     */
    public function __construct(RoleModel $role)
    {
        $this->role = $role;
        $this->DefaultRoles = $this->getDefaultRoleStringArray();
    }
 
    protected function getDefaultRoleStringArray() : array
    {
        return RoleModel::getDefaultRolesOrFail();
    }

    /**
     * @param Request|array $request
     * @return $this
     * @throws Exception
     */
    protected function initValidator(Request | array $request): self
    {
        $request->merge(["role_id" => $this->role->id]);
        $this->validator = new JSONValidator($this->getRequestFormClass(), $request);
        return $this;
    }

    protected function IsDefaultRole(): bool
    {
        return in_array($this->role->name, $this->DefaultRoles);
    }

    protected function getRequestFormClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(RoleUpdatingRequest::class);
    }

    /**
     * @return $this
     */
    protected function setRequestData(): self
    {
        $this->data = $this->validator->getRequestData();
        return $this;
    }

    /**
     * @return $this
     * @throws JsonException
     */
    protected function validateData(): self
    {
        $this->validator->validate();
        return $this;
    }


    //Here We Called The Common Operations (Validation .... etc)
    public function change(Request | array $data): JsonResponse
    {
        try {
            $this->initValidator($data)->setRequestData();
            $this->validateData();

            if ($this->role->user()->count() != 0 && isset($this->data["disabled"]) &&$this->data["disabled"] == 1) {
                throw new Exception("Role can not be deactivated as it has assigned users ");
            }
            return $this->changerFun();

        } catch (Exception $e) {
            return $this->getErrorResponse([$e->getMessage()]);
        }
    }

    protected function getErrorResponse(array $messages): JsonResponse
    {
        return Response::error($messages);
    }

    /**
     * @return JsonResponse
     * This Method Will Execute The Desired Updating Actions
     */
    abstract protected function changerFun(): JsonResponse;
}
