<?php

namespace  PixelApp\Services\UsersManagement\ShowServices;

use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use Illuminate\Support\Facades\Response; 
use PixelApp\Models\PixelModelManager; 
use PixelApp\Services\CoreServices\ModelShowService;

class UserTypeShowService extends ModelShowService
{ 
    public function __construct(int $key, string $fetchingColumn = 'id')
    { 
        BasePolicy::check('readEmployees', $this->getModelClass());

        parent::__construct($key , $fetchingColumn);
    }

    protected function getModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }

    protected function getModelData() : array
    {
        return $this->model->only("id", "department_id", "role_id", "status");
    }

    protected function respond()
    {     
        $data = ["item" => $this->getModelData()];
        return Response::success($data);
    }
}
