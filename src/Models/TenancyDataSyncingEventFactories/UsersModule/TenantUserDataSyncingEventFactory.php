<?php

namespace PixelApp\Models\TenancyDataSyncingEventFactories\UsersModule;

use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\AdminPanelRouteIdentifierFactories\CompanyAuthRouteIdentifierFactories\DefaultAdminDataSyncingRouteIdentifierFactory;
use PixelApp\CustomLibs\PixelCycleManagers\PixelAppsConnectionManagement\PixelAppRouteIdentifiersFactories\PixelAppRouteIdentifierFactory;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Events\TenancyEvents\DataSyncingEvents\FromTenantSideEvents\MonolithAppEvents\CentralDBDataSyncingEvent;
use PixelApp\Events\TenancyEvents\DataSyncingEvents\FromTenantSideEvents\SeparatedTenantAppEvents\AdminPanelDBDataSyncingEvent;
use PixelApp\Events\TenancyEvents\DataSyncingEvents\TenancyDataSyncingEvent;
use PixelApp\Events\TenancyEvents\DataSyncingEvents\TenancyDataSyncingEventFactories\TenancyDataSyncingEventFactory;
use PixelApp\Models\CompanyModule\CompanyDefaultAdmin;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\UsersModule\PixelUser;

class TenantUserDataSyncingEventFactory extends TenancyDataSyncingEventFactory
{

    protected PixelUser $user;
    protected string $centralModelIdKeyName;
    protected string $centralModelClass ;

    public function __construct(PixelUser $user)
    {
        $this->user = $user;
        $this->setCentralAppModelClass();
        $this->setCentralModelIdKeyName();
    }


    protected function setCentralAppModelClass() : void
    {
        $this->centralModelClass = PixelModelManager::getModelForModelBaseType(CompanyDefaultAdmin::class);
    }

    protected function initDefaultAdminModel() : CompanyDefaultAdmin
    {
        $defaultAdminClass = $this->getCentralAppModelClass();
        return new $defaultAdminClass();
    }

    protected function setCentralModelIdKeyName() : void
    {
        $defaultAdmin = $this->initDefaultAdminModel();
        $this->centralModelIdKeyName = $defaultAdmin->getEmailColumnName();
    }

    public function createTenancyDataSyncingEvent() : ?TenancyDataSyncingEvent
    {
        if(PixelTenancyManager::isItTenantApp())
        {
            return $this->initEventForSyncingAdminPanelCase();
        }

        if(PixelTenancyManager::isItMonolithTenancyApp())
        {
            return $this->initEventForSyncingCentralAppCase();
        }

        return null;
    }

    protected function getCompanyDomain() : string
    {
        return tenant()->domain;
    }

    protected function initAdminPanelDataSyncingRouteIdFactory() : PixelAppRouteIdentifierFactory
    {
        $companyDomain = $this->getCompanyDomain();
        return new DefaultAdminDataSyncingRouteIdentifierFactory($companyDomain);
    }

    protected function initEventForSyncingAdminPanelCase() :  AdminPanelDBDataSyncingEvent
    {
        return new AdminPanelDBDataSyncingEvent(
                        $this->initAdminPanelDataSyncingRouteIdFactory()
               );
    }


    protected function initEventForSyncingCentralAppCase() :  CentralDBDataSyncingEvent
    {
        return new CentralDBDataSyncingEvent(
                    $this->getCentralAppModelClass(),
                    $this->getCentralModelIdKeyName(),
                    $this->getCentralModelIdKeyValue(),
                    $this->getUpdatedData()
                );
    }

    public function getCentralAppModelClass(): string
    {
        return $this->centralModelClass;
    }

    public function getCentralModelIdKeyName(): string
    {
        return $this->centralModelIdKeyName;
    }

    /**
     * @return int|string
     * it is an alias for getOriginalIdentifierValue method
     */
    public function getCentralModelIdKeyValue(): int|string
    {
        $keyName = $this->getCentralModelIdKeyName();
        return $this->user->getTempCatchedKeyValue($keyName) ?? $this->user->{$keyName};
    }

    protected function getUpdatedData() : array
    {
        return $this->user->only( $this->getSyncedAttributeNames() );
    }

    public function getSyncedAttributeNames(): array
    {
        /**
         * Here we can return any field we want ... there is no need to return the fields those are exist in fillables
         * because we are here customize the data will be saved in database ... it is not data coming from the request
         */
        return [
            $this->user->getEmailColumnName(),
            $this->user->getEmailVerificationDateColumnName(),
            $this->user->getEmailVerificationTokenColumnName(),
            'first_name',
            'last_name',
            'name',
            'password',
            'mobile',
        ];
    }
}