<?php

namespace PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Traits;

use Exception; 

trait ExpectsSensitiveRequestDataFunc
{
    protected array $data = [];
    protected ?string $propRequestKeyName = null;


    /**
     * @param array $data
     * @return $this
     */
    public function setData(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param string $propRequestKeyName
     * @return $this
     */
    public function setPropRequestKeyName(string $propRequestKeyName): self
    {
        $this->propRequestKeyName = $propRequestKeyName;
        return $this;
    }

    public function getPropRequestKeyDefaultName(): mixed
    {
        return null;
    }

    /**
     * @return string
     * @throws Exception
     */
    protected  function getPropRequestKeyName(): string
    {
        if(!$this->propRequestKeyName)
        {
            $this->propRequestKeyName = $this->getPropRequestKeyDefaultName();
        }

        return $this->propRequestKeyName ??
               throw new Exception("The property request default key name value is not set !");
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getPropNewRequestValue() : mixed
    {
        return $this->data[ $this->getPropRequestKeyName() ]  ?? null;
    }

    protected function composeChangesArray(mixed $value) : array
    {
        return $value ? [ $this->getPropName() => $value] : [];

    }
}
