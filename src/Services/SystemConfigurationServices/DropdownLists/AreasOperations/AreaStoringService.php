<?php

namespace PixelApp\Services\SystemConfigurationServices\DropdownLists\AreasOperations;

use App\Http\Requests\WorkSector\SystemConfigurations\Areas\StoringAreaRequest as AreasStoringAreaRequest;
use App\Models\WorkSector\CountryModule\Area;
use CRUDServices\CRUDServiceTypes\DataWriterCRUDServices\StoringServices\MultiRowStoringService;

class AreaStoringService extends MultiRowStoringService
{

    protected function getModelCreatingFailingErrorMessage(): string
    {
        return "Failed To Create The Given Area !";
    }

    protected function getModelCreatingSuccessMessage(): string
    {
        return "The Area Has Been Created Successfully !";
    }

    protected function getModelClass(): string
    {
        return Area::class;
    }

    protected function getRequestClass(): string
    {
        return AreasStoringAreaRequest::class;
    }

}
