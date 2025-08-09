<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\CurrenciesOperations;

use Illuminate\Support\Facades\Response;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\Currency;
use PixelApp\Services\CoreServices\ModelListingService;

class CurrenciesListingService extends ModelListingService
{
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
                                            'symbol'
                                        ]
                                    );
    }

    protected function getSelectedColumns() : array
    {
        return ['id', 'name', 'code' , 'symbol'];
    }

    protected function setCustomScopes() : void
    {
        $this->query->active()
                    ->customOrdering('created_at', 'desc');
    }

    protected function getTotalActiveCurrencies() : int
    {
        $modelClass = $this->getModelClass();
        return $modelClass::active()->count();
    }

    protected function respond($data)
    {
        $total = $this->getTotalActiveCurrencies();
            
        return Response::successList($total, $data->toArray()); 
    }
   
}
