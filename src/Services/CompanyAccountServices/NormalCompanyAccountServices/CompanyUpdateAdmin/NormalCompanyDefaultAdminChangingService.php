<?php

namespace PixelApp\Services\CompanyAccountServices\NormalCompanyAccountServices\CompanyUpdateAdmin;

use Exception;
use PixelApp\Interfaces\EmailAuthenticatable;
use PixelApp\Models\CompanyModule\CompanyAccountModels\CompanyAccount; 
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\UsersModule\PixelUser;
use PixelApp\Services\CompanyAccountServices\BaseServices\CompanyUpdateAdmin\CompanyDefaultAdminChangingBaseService;
 
class NormalCompanyDefaultAdminChangingService extends CompanyDefaultAdminChangingBaseService
{
 
    protected function getCompanyAccountModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(CompanyAccount::class);
    }

    protected function getCompanyAccount() : CompanyAccount
    {
        $companyAccountClass = $this->getCompanyAccountModelClass();
        return $companyAccountClass::first() 
               ??
               throw new Exception("Missed data ... no company account is found in the system !");
    }

    protected function fetchCurrentDefaultUserAdmin() : ?PixelUser
    {
        $user = $this->getCompanyAccount()->getDefaultAdmin();
        
        if($user instanceof PixelUser || $user instanceof EmailAuthenticatable)
        {
            return $user;
        }

        throw new Exception("The current default admin model 
                            must be a child type of PixelApp\Models\UsersModule\PixelUser 
                            or must implement PixelApp\Interfaces\EmailAuthenticatable interface"
                           );
    }  
    
}
