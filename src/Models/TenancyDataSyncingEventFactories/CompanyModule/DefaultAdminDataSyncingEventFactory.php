<?php

namespace PixelApp\Models\TenancyDataSyncingEventFactories\CompanyModule;

use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Events\TenancyEvents\DataSyncingEvents\FromCentralSideEvents\TenantDBDataSyncingEvent;
use PixelApp\Events\TenancyEvents\DataSyncingEvents\TenancyDataSyncingEvent;
use PixelApp\Events\TenancyEvents\DataSyncingEvents\TenancyDataSyncingEventFactories\TenancyDataSyncingEventFactory;
use PixelApp\Models\CompanyModule\CompanyDefaultAdmin;
use PixelApp\Models\CompanyModule\TenantCompany;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\UsersModule\PixelUser; 

class DefaultAdminDataSyncingEventFactory extends TenancyDataSyncingEventFactory
{

    protected string $tenantAppModelClass;
    protected string $tenantModelIdKeyName;
    protected CompanyDefaultAdmin $admin;

    public function __construct(CompanyDefaultAdmin $admin)
    {
        $this->admin = $admin;
        $this->setTenantAppModelClass();
        $this->setTenantModelIdKeyName();
    }


    public function createTenancyDataSyncingEvent() : ?TenancyDataSyncingEvent
    {
        if(PixelTenancyManager::isItAdminPanelApp() || PixelTenancyManager::isItMonolithTenancyApp())
        {
            return $this->initEventForSyncingTenantSideCase();
        }

        return null;
    }
  
    
    protected function setTenantAppModelClass() : void
    {
        $this->tenantAppModelClass = PixelModelManager::getUserModelClass();
    }

    protected function initUserModel() : PixelUser
    {
        $userClass = $this->getTenantAppModelClass();
        return new $userClass();
    }

    protected function setTenantModelIdKeyName() : void
    {
        $user = $this->initUserModel();
        $this->tenantModelIdKeyName = $user->getEmailColumnName();
    }

    protected function initEventForSyncingTenantSideCase() :  TenantDBDataSyncingEvent
    {
        return new TenantDBDataSyncingEvent(
                    $this->getTenant(),
                    $this->getTenantAppModelClass(),
                    $this->getTenantModelIdKeyName(),
                    $this->getTenantModelIdKeyValue(),
                    $this->getModelUpdatedData(),
                    $this->getModelRelationsUpdatedData()
                );
    }

    protected function getTenant() : TenantCompany
    {
        return $this->admin->tenant;
    }
    
    public function getTenantAppModelClass(): string
    {
        return $this->tenantAppModelClass ;
    }
 
    public function getTenantModelIdKeyName(): string
    {
        return $this->tenantModelIdKeyName;
    }

    /**
     * @return int|string
     */
    public function getTenantModelIdKeyValue(): int|string
    {
        $keyName = $this->getTenantModelIdKeyName();
        return $this->admin->getTempCatchedKeyValue($keyName) ?? $this->admin->{$keyName};
    }

    protected function getModelUpdatedData() : array
    {
        return $this->admin->only( $this->getSyncedModelAttributeNames() );
    }
    
    public function getSyncedModelAttributeNames(): array
    {
        /**
         * Here we can return any field we want ... there is no need to return the fields those are exist in fillables
         * because we are here customize the data will be saved in database ... it is not data coming from the request
         */
        return [
            $this->admin->getEmailColumnName() ,
            $this->admin->getEmailVerificationDateColumnName(),
            $this->admin->getEmailVerificationTokenColumnName(),
            'first_name',
            'last_name',
            'name',
            'password',
            'mobile',

        ];
    }
 
    
   protected function getModelRelationsUpdatedData() : array
   {
        return $this->getSyncedAdminProfileUpdatedData();
   }

    public function getSyncedAdminProfileUpdatedData(): array
    {
        return [
                    "profile" => [
                        "nationality_id" => $this->Admin->nationality_id ?? null 
                    ]
                ];
    } 
}