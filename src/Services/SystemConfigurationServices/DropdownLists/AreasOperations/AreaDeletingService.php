<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\AreasOperations;

use CRUDServices\CRUDServiceTypes\DeletingServices\DeletingService;

class AreaDeletingService extends DeletingService
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
