<?php

namespace Database\Factories;

use App\Models\WorkSector\CompanyModule\TenantCompany;
use App\Models\WorkSector\CountryModule\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = TenantCompany::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'company_domain' => $this->faker->domainName,
            'company_sector' => $this->faker->randomElement(['IT', 'Construction', 'Finance']),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'country_id' => Country::first()->id,
            'registration_status' => $this->faker->randomElement('pending'),
            'branches_no' => $this->faker->numberBetween(1, 10),
            'admin_email' => $this->faker->email,
            'contacts' => [
                [
                    "contact_number" => $this->faker->phoneNumber,
                    "contact_number_type" => $this->faker->randomElement(['whatsapp', 'phone'])
                ]
            ]
        ];
    }
}
