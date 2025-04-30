<?php

namespace PixelApp\Models\CompanyModule;

use PixelApp\Models\PixelBaseModel ;
use PixelApp\Interfaces\EmailAuthenticatable;
use PixelApp\Traits\interfacesCommonMethods\EmailAuthenticatableMethods;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Events\TenancyEvents\DataSyncingEvents\TenancyDataSyncingEvent;
use PixelApp\Interfaces\OnlyAdminPanelQueryable;
use PixelApp\Interfaces\TenancyInterfaces\CanSyncData;
use PixelApp\Models\Interfaces\TrustedAttributesHandlerModel;
use PixelApp\Models\TenancyDataSyncingEventFactories\CompanyModule\DefaultAdminDataSyncingEventFactory;
use PixelApp\Models\Traits\TrustedAttributesHandlerModelMethods;

class CompanyDefaultAdmin 
      extends PixelBaseModel
      implements EmailAuthenticatable ,OnlyAdminPanelQueryable , CanSyncData , TrustedAttributesHandlerModel

{
    //laravel traits
    use Notifiable  ;

    //pixel custom traits
    use EmailAuthenticatableMethods , TrustedAttributesHandlerModelMethods;

    protected $table = "tenant_default_admins";
    protected $fillable = [
        'email',
        'name',
        'first_name',
        'last_name',
        'password',
        'mobile',
        'company_id'
    ];
 
    public function getConnectionName()
    {
        return config("database.defaultCentralConnection");
    }
 
    public function tenant()  : BelongsTo
    {
        return $this->belongsTo( PixelTenancyManager::getTenantCompanyModelClass() , "company_id" , "id");
    }

    /**
     * @return TenantCompany
     */
    public function Company(): TenantCompany
    {
        return $this->tenant;
    }

    public function getTenancyDataSyncingEvent() : ?TenancyDataSyncingEvent
    {
        if($this->canSyncData())
        {
            return (new DefaultAdminDataSyncingEventFactory($this))->createTenancyDataSyncingEvent();
        }

        return null;
        
    }

    public function canSyncData() :bool
    {
        return $this->tenant->isApproved();
    }
}
