<?php

namespace PixelApp\Database\Factories\UserModule;


use PixelApp\Models\UsersModule\PixelUser as User;

class AcceptedUserFactory extends UserFactory
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
                'status' => User::USER_STATUS[$this->faker->numberBetween(0,1)],
                'accepted_at' => now(),
                'user_type' => 'user'
            ]
        );
    }
}
