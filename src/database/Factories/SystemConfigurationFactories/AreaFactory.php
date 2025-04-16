<?php

namespace PixelApp\Database\Factories\SystemConfigurationFactories;
 
use PixelApp\Database\Factories\PixelBaseFactory as Factory;
use PixelApp\Models\SystemConfigurationModels\CountryModule\Area;
use PixelApp\Models\SystemConfigurationModels\CountryModule\City;

class AreaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Area::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->city,
            'city_id' => City::factory()
        ];
    }
}
