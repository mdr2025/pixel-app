<?php

namespace PixelApp\Database\Factories\UserModule;
 

class SignUpUserFactory extends UserFactory
{

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return array_merge(
            parent::definition(),
            [
                'status' => "pending",
            ]
        );
    }
}
