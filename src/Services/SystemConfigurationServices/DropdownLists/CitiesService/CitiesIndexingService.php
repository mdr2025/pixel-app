<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\CitiesServices;

use Illuminate\Support\Facades\Response; 
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\CountryModule\City;
use PixelApp\Services\CoreServices\ModelIndexingService;

class CitiesIndexingService extends ModelIndexingService
{
    protected function getModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(City::class);
    }
    protected function setCustomScopes() : void
    {
        $this->query->customOrdering('id', 'asc');
    }
    protected function setAllowedFilters() : void
    {
        $this->query->allowedFilters(['name', 'country_id']);
    }

    protected function eagerLoadRelations() : void
    {
        $this->query->with(['country' ]);
    }

    protected function respond($data)
    { 
        return Response::success(['list' => $data ]);
    }
   
}
