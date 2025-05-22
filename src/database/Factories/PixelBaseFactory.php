<?php

namespace PixelApp\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory as BaseFactory;
use PixelApp\Models\PixelModelManager;

class PixelBaseFactory extends BaseFactory
{

    protected function getAltModelOrBase(string $model) : string
    {
        return PixelModelManager::getModelForModelBaseType($model);
    }

    //editing behavior to getting the alternative model if it is set
    public function __get($name)
    {
        $propValue = parent::__get($name);

        if($name == "model")
        {
            return $this->getAltModelOrBase($propValue);
        }

        return $propValue;
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            //
        ];
    }
}
