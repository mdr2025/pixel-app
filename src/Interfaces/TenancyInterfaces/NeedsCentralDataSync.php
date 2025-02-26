<?php

namespace PixelApp\Interfaces\TenancyInterfaces;


/**
 * it means : The tenant model needs to sync its data with central database
 */
interface NeedsCentralDataSync extends NeedsTenancyDataSync
{
    public function getCentralAppModelClass(): string;
    public function getCentralAppModelIdentifierKeyName(): string;
    public function getCentralAppModelIdentifierOriginalValue();
}
