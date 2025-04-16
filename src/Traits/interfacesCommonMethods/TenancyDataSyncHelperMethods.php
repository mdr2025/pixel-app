<?php

namespace PixelApp\Traits\interfacesCommonMethods;

use PixelApp\Interfaces\TenancyInterfaces\NeedsCentralDataSync;
use PixelApp\Interfaces\TenancyInterfaces\NeedsTenancyDataSync;
use PixelApp\Interfaces\TenancyInterfaces\NeedsTenantDataSync;

trait TenancyDataSyncHelperMethods
{
    public int|string|null $originalIdentifierValue = null;

    protected function determineIdentifierKeyName() : string
    {
        if($this instanceof NeedsCentralDataSync)
        {
            return $this->getCentralAppModelIdentifierKeyName();
        }
        if($this instanceof NeedsTenantDataSync)
        {
            return $this->getTenantAppModelIdentifierKeyName();
        }
        return $this->getKeyName();
    }
    
    protected function determineIdentifierKeyValue() : string|int
    {
        if($this instanceof NeedsTenancyDataSync)
        {
            return $this->getOriginal( $this->determineIdentifierKeyName() );
        }
        return $this->getKey();
    }
    /**
     * @return void
     * Must use it before saving model
     */
    public function setOriginalIdentifierValue() : void
    {
        $this->originalIdentifierValue = $this->determineIdentifierKeyValue();
    }

    /**
     * @return int|string
     */
    public function getOriginalIdentifierValue(): int|string
    {
        return $this->originalIdentifierValue ?? $this->determineIdentifierKeyValue();
    }
}
