<?php

namespace PixelApp\Database\Factories\UserModule;

 
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
                'status' => "active",
                'accepted_at' => now(),
                'user_type' => 'user'
            ]
        );
    }
}
