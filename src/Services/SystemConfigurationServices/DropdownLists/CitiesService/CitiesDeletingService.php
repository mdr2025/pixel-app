<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\CitiesServices;

use CRUDServices\CRUDServiceTypes\DeletingServices\DeletingService;

class CitiesDeletingService extends DeletingService
{
    protected function getModelDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given City!";
    }

    protected function getModelDeletingSuccessMessage(): string
    {
        return "The City Has Been Deleted Successfully!";
    }
 
}
