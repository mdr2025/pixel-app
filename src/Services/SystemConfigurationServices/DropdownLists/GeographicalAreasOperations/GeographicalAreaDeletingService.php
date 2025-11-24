<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\GeographicalAreasOperations;

use CRUDServices\CRUDServiceTypes\DeletingServices\DeletingService;

class GeographicalAreaDeletingService extends DeletingService
{

    protected function getModelDeletingFailingErrorMessage(): string
    {
        return "Failed To Delete The Given Area";
    }

    protected function getModelDeletingSuccessMessage(): string
    {
        return "The Area Has Been Deleted Successfully !";
    }


}
