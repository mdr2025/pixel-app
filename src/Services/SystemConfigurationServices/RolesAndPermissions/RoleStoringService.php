<?php

namespace PixelApp\Services\SystemConfigurationServices\RolesAndPermissions;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\SystemConfigurationRequests\RoleRequests\RoleStoringRequest;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\RoleModel;
use Spatie\Permission\Models\Permission; 
use ValidatorLib\JSONValidator;
use ValidatorLib\Validator;

class RoleStoringService
{

    private Validator $validator;
    private Model | RoleModel | null $role = null;
    private array $data = [];


    protected function getRequestFormClass() : string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(RoleStoringRequest::class);
    }
    /**
     * @param Request|array $request
     * @return $this
     * @throws Exception
     */
    public function initValidator(): self
    {
        $this->validator = new JSONValidator($this->getRequestFormClass());
        return $this;
    }

    
    protected function getRoleModeClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(RoleModel::class);
    }

    /**
     * @param string $name
     * @return $this
     * @throws Exception
     */
    private function createRole(): self
    { 
        $modelClass = $this->getRoleModeClass();
        $role = $modelClass::create(["name" => $this->data["name"] ]);

        if (! $this->role = $role ) 
        {
            throw new Exception("Failed To Create The Given Role !");     
        }
        return $this;
        
    }

    private function PermissionsArrayHandler(array $permissions): array
    {
        $permissionClass = PixelModelManager::getModelForModelBaseType(Permission::class);
        return $permissionClass::whereIn("name", $permissions)->orderBy("id")->pluck("id")->toArray();
    }

    /**
     * @param array $data
     * @return $this
     * @throws Exception
     */
    private function createPermissionRelationships(): void
    {
        $permissions = $this->PermissionsArrayHandler($this->data["permissions"]);
        if (!$this->role->syncPermissions($permissions)) 
        {
            throw  new Exception("Permissions Has Not Been Bind To The Given Rule .... Operation Has Been Canceled !");
        }
    }

    protected function setRequestData() : void
    {
        $this->data = $this->validator->getRequestData();
    }
    /**
     * @return bool
     * @throws Exception
     */
    private function validateData(): void
    {
        $this->validator->validate();
    }

    public function create(): JsonResponse
    {
        try {
            $this->initValidator(); 
            $this->validateData();
            $this->setRequestData();

            //Validation Exceptions (If There are) Will Be Thrown Before DB Transaction
            DB::beginTransaction();

            $this->createRole()->createPermissionRelationships();

            //If No Exception Is Thrown .... Transaction Will Be Commit
            DB::commit();
            return Response::success([], ["Role Has Been Created Successfully"]);
        } catch (Exception $e) {
            DB::rollBack();
            return Response::error([$e->getMessage()]);
        }
    }
}
