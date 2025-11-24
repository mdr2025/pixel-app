<?php

namespace PixelApp\Database\Factories\SystemConfigurationFactories;
 
use PixelApp\Database\Factories\PixelBaseFactory as Factory;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\CountryModule\GeographicalArea;
use PixelApp\Models\SystemConfigurationModels\CountryModule\City;

class GeographicalGeographicalAreaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GeographicalArea::class;

    protected function getCityFactory()
    {
        $cityModelClass = PixelModelManager::getModelForModelBaseType(City::class);
        return $cityModelClass::factory();
    }
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->city,
            'city_id' => $this->getCityFactory()
        ];
    }
}

