<?php

namespace PixelApp\Database\Factories\UserModule;

use PixelApp\Models\UsersModule\PixelUser as User;

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
