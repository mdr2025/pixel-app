<?php

namespace PixelApp\Services\Traits;

use ValidatorLib\JSONValidator;
use ValidatorLib\Validator;

trait GeneralValidationMethods
{
    protected ?Validator $validator = null;
    protected array $data = [];

    protected function getRequestFormClass() : string
    {
        /**
         * Must be overwritten from child when using
         */
        return "";
    }
    
    protected function setRequestData() : self
    {
        $this->initValidator();
        $this->data = $this->validator->getRequestData();
        return $this;
    }

    protected function initValidator(): self
    {
        if(!$this->validator){$this->validator = new JSONValidator($this->getRequestFormClass());}
        return $this;
    }

    protected function changeRequestData(array $newDataArray) : self
    {
        $this->initValidator();
        $this->validator->setRequestData($newDataArray);
        return $this;
    }
    protected function validateRequest(): self
    {
        $this->validator->applyBailRule()->validate();
        return  $this;
    }

}
