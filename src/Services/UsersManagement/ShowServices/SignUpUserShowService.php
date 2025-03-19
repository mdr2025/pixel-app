<?php

namespace  PixelApp\Services\UsersManagement\ShowServices;

use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use Illuminate\Support\Facades\Response; 
use PixelApp\Models\PixelModelManager; 
use PixelApp\Services\CoreServices\ModelShowService;

class SignUpUserShowService extends ModelShowService
{ 
    public function __construct(int $key, string $fetchingColumn = 'id')
    { 
        BasePolicy::check('readSignUpList', $this->getModelClass());

        parent::__construct($key , $fetchingColumn);
    }

    protected function getModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }

    protected function respond()
    {   
        return Response::success(["item" => $this->model->only("id" , "email")]); 
    }
}
