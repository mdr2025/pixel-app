<?php

namespace PixelApp\Models\CompanyModule\CompanyAccountModels;

use Exception;
use PixelApp\Interfaces\EmailAuthenticatable;
use PixelApp\Models\CompanyModule\PixelCompany\PixelCompany;
use PixelApp\Models\PixelModelManager;

class CompanyAccount extends PixelCompany
{

    protected ?EmailAuthenticatable  $defaultAdmin = null;

    protected $table = "company_account";
   

    protected function getUserModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }

    protected function initDefaultAdmin() : EmailAuthenticatable
    {
        return $this->getUserModelClass()::defaultUser()->first()
               ??
               throw new Exception("Missed data seeding .... a default user must be assigned to the comapny account !");
    }

    public function getDefaultAdmin()  : EmailAuthenticatable
    {
        if(!$this->defaultAdmin)
        {
            $this->defaultAdmin = $this->initDefaultAdmin();
        }

        return $this->defaultAdmin;
    }
          
    public function getDocumentsStorageFolderName(): string
    {
        return "company/" . $this->hashed_id;
    }
}