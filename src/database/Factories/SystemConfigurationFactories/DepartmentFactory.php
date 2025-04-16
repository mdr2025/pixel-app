<?php

namespace PixelApp\Database\Factories\SystemConfigurationFactories;

use PixelApp\Database\Factories\PixelBaseFactory as Factory;

class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'status'=>$this->faker->randomElement([1,0]),
            'created_at' => $this->faker->dateTimeBetween('-3 year', '+3 year'),
            'updated_at' => $this->faker->dateTimeBetween('-3 year', '+3 year'),
        ];
    }
}
