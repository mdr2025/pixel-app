<?php

namespace PixelApp\Database\Seeders\UserModuleSeeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use PixelApp\Models\PixelModelManager; 

class SignUpUserSeeder extends Seeder
{


    protected function getUSerModelClass() : string
    {
        return PixelModelManager::getUSerModelClass();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $factory = new SignUpUserFactory();
        // $factory->count(200)
        //         ->has( UserProfile::factory()->count(1) )
        // 
        
        for ($i = 60000; $i < 60100;$i++){
            $firstName ='usman '.$i;
            $lastName ='ahmed '.$i;

            $this->getUSerModelClass()::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'name' => $firstName . " " . $lastName,
                'email' => "usmanahmed{$i}@gmail.com",
                'password' => Hash::make(123456789),
                'mobile' => $i,
                'role_id'=>2,
                'status'=>'active',
                'user_type'=>'user',
                'email_verified_at' => Carbon::now(),
                'accepted_at' => Carbon::now(),
                'verification_token' => null,
                'remember_token' => null,
            ]);
        }
    }
}
