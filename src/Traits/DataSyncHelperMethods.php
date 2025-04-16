<?php

namespace PixelApp\Traits;

 
trait DataSyncHelperMethods
{
    protected array $tempKeys = [];
  
    public function catchKeyOriginalValueTemporarlly(?string $key = null) : void
    {
        if(!$key)
        {
            $key = $this->getKeyName();
        }

        $this->tempKeys[$key] = $this->getOriginal($key);
    }

    
    public function catchKeyValueTemporarlly(?string $key = null) : void
    {
        if(!$key)
        {
            $key = $this->getKeyName();
        }

        $this->tempKeys[$key] = $this->getAttribute($key);
    }

    
    public function getTempCatchedKeyValue(?string $key = null): mixed
    {
        if(!$key)
        {
            $key = $this->getKeyName();
        }

        return $this->tempKeys[$key] ?? null;
    }
}
