<?php

namespace PixelApp\Database\Factories\SystemConfigurationFactories;
 
use PixelApp\Database\Factories\PixelBaseFactory as Factory;
use PixelApp\Models\SystemConfigurationModels\CountryModule\City;
use PixelApp\Models\SystemConfigurationModels\CountryModule\Country;

class CityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = City::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->state,
            'country_id' => Country::factory()
        ];
    }
}
