<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\CurrenciesOperations;

use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use Illuminate\Support\Facades\Response; 
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Currency;
use PixelApp\Services\CoreServices\ModelIndexingService;

class CurrenciesIndexingService extends ModelIndexingService
{ 

    public function __construct()
    {
        BasePolicy::check('read', Currency::class);
    }

    protected function getModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(Currency::class);
    }
 
    protected function setAllowedFilters() : void
    {
        $this->query->allowedFilters(
                                        [
                                            'name',
                                            'code',
                                            'symbol',
                                            'symbol_native',
                                            'status'
                                        ]
                                    );
    }

    protected function respond($data)
    { 
        return Response::success(['list' => $data]); 
    }
   
}
