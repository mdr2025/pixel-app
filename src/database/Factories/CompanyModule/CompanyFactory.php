<?php

namespace PixelApp\Database\Factories\CompanyModule;
 
use PixelApp\Database\Factories\PixelBaseFactory as Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PixelApp\Models\CompanyModule\TenantCompany;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\CountryModule\Country;

class CompanyFactory extends Factory
{
    protected $model = TenantCompany::class;

    protected int $companyId = 0;
    protected array $companySectors = [
        'Software & Technology',
        'Oil & Gas',
        'Petrochemical',
        'Mining',
        'Logistics & Ports',
        'Construction',
        'Automotive',
        'Cement Industry',
        'Food & Beverage',
        'Pharmaceutical',
        'Healthcare',
        'Fashion and Apparel',
        'Chemical',
        'Industrial',
        'Real Estate',
        'Consulting',
        'Education',
        'Insurance',
        'Financial Services',
        'Renewable Energy',
        'Telecommunications',
        'Agricultural',
        'Advertising',
        'E-commerce',
        'Defense and Security',
        'Retail',
        'Entertainment',
        'Gaming',
    ];
    protected array $employeesNumber = [
        '1 - 5',
        '6 - 10',
        '11 - 15',
        '16 - 20',
        '21 - 50',
        ' > 50'
    ];

    protected function getAltModelOrBase(string $model) : string
    {
        return PixelModelManager::getTenantCompanyModelClass();
    }

    protected function getFirstCountryId() : int
    {
        $countryModelClass = PixelModelManager::getModelForModelBaseType(Country::class);
        return $countryModelClass::first()->id;
    }

    protected function generateCompanyId() : int
    {
        return $this->companyId++;
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        
        $companyId = $this->generateCompanyId();
        $first_name  = $this->faker->name();
        $last_name = $this->faker->name();
        return [
            'id' => $companyId,
            'name' => $this->faker->company,
            'domain' => $this->faker->domainName,
            'sector' => $this->faker->randomElement($this->companySectors),
            'country_id' => $this->getFirstCountryId(),
            'status' => $this->faker->randomElement('pending'),
            'branches_no' => $this->faker->numberBetween(1, 10),
            'employees_no' => $this->faker->randomElement($this->employeesNumber),
            'defaultAdmin' => [
                'email' => $this->faker->safeEmail(), 
                'name' => $first_name . " " . $last_name,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'password' => Hash::make(22442244),
                'mobile'=> $this->faker->phoneNumber,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'verification_token' => Str::random(10),
                'created_at' => $this->faker->dateTimeBetween('-3 year', '+3 year'),
                'updated_at' => $this->faker->dateTimeBetween('-3 year', '+3 year'),
                'company_id' => $companyId
            ],
            'contacts' => [
                [
                    "contact_number" => $this->faker->phoneNumber,
                    "contact_number_type" => $this->faker->randomElement(['whatsapp', 'phone'])
                ]
            ]
        ];
    }
}
