<?php

namespace PixelApp\Database\Factories\UserModule;


use CustomFileSystem\CustomFileUploader;
use PixelApp\Database\Factories\PixelBaseFactory as Factory;
use Illuminate\Support\Collection;
use PixelApp\Models\SystemConfigurationModels\CountryModule\Country as CountryModuleCountry;
use PixelApp\Models\SystemConfigurationModels\Department;
use PixelApp\Models\SystemConfigurationModels\RoleModel;
use PixelApp\Models\UsersModule\PixelUser;
use PixelApp\Models\UsersModule\UserProfile;

class UserProfileFactory extends Factory
{

    protected CustomFileUploader $uploader;
    protected PixelUser $user;

    public function __construct($count = null, ?Collection $states = null, ?Collection $has = null, ?Collection $for = null, ?Collection $afterMaking = null, ?Collection $afterCreating = null, $connection = null , PixelUser $user)
    {
        parent::__construct($count, $states, $has, $for, $afterMaking, $afterCreating, $connection);
        $this->user = $user;
        // $this->uploader = new S3CustomFileUploader();
        $this->setCountryIDS();
    }

    // protected function fakeAvatarFile() : string
    // {
    //     $fileKey = "avatar";
    //     $folder = $this->user->getDocumentsStorageFolderName() . $fileKey;
    //     return $this->uploader->fakeSingleFile($fileKey , $folder);
    // }

    // protected function fakeNationalIdFiles() : string
    // {
    //     $filesKey = "national_id_files";
    //     $folder = $this->user->getDocumentsStorageFolderName() . $filesKey;
    //     return json_encode($this->uploader->fakeMultiFiles($filesKey , $folder));
    // }

    // protected function getPassportFiles() : string
    // {
    //     $filesKey = "passport_files";
    //     $folder = $this->user->getDocumentsStorageFolderName() . $filesKey;
    //     return json_encode($this->uploader->fakeMultiFiles($filesKey , $folder));
    // }


    protected array $genders = ["male" , "female"];

    protected array $countryIDS = [];
    protected int $countriesCount ;
    protected function setCountryIDS()
    {
        $this->countryIDS = CountryModuleCountry::pluck("id")->toArray();
        $this->countriesCount = count($this->countryIDS);
    }

    protected $model = UserProfile::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // $national_id_files = $this->fakeNationalIdFiles();
        // $avatar = $this->fakeAvatarFile();
        // $passport_files = $this->getPassportFiles();
        $this->uploader->uploadFiles();
        return [
            'gender' => $this->genders[$this->faker->numberBetween(0,1)],
            'national_id_number' => $this->faker->randomDigit(),
            'national_id_files' => null,
            'passport_files' => null,
            'passport_number' => $this->faker->randomDigit(),
            'avatar' => null,
            "country_id" => $this->countryIDS[rand(0 , $this->countriesCount)]
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }


    protected array $RoleIDS = [];
    protected int $RolesCount = 1;
    protected function setRoleIDS()
    {
        $this->RoleIDS = RoleModel::pluck("id")->toArray();
        $this->RolesCount = count($this->RoleIDS);
    }

    public function withRoleState() : Factory
    {
        $this->setRoleIDS();
        return $this->state(function(array $attributes){
            return [
                'role_id' => $this->RoleIDS[rand(0 , $this->RolesCount)] ?? null
            ];
        });
    }

    protected array $DepartmentIDS = [];
    protected int $DepartmentsCount = 1;
    protected function setDepartmentIDS()  : Factory
    {
        $this->DepartmentIDS = Department::pluck("id")->toArray();
        $this->DepartmentsCount = count($this->DepartmentIDS);
        return $this;
    }
    public function withDepartmentState()
    {
        $this->setDepartmentIDS();
        return $this->state(function(array $attributes){
            return [
                'department_id' => $this->DepartmentIDS[rand(0 , $this->DepartmentsCount)] ?? null
            ];
        });
    }
}
