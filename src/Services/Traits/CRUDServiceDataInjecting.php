<?php

namespace PixelApp\Services\Traits;

trait CRUDServiceDataInjecting
{
    protected array $injectedData;

    protected function doBeforeValidation(): void
    {
        $this->validationManager?->setValidatorData($this->getInjectedData());
    }

    public function injectDataBeforeValidation(array $data):void
    {
        $this->injectedData = $data;
    }

    protected function getInjectedData():array
    {
        return $this->injectedData;
    }
}