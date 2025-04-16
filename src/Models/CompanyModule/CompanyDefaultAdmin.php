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
use PixelApp\Models\TenancyDataSyncingEventFactories\CompanyModule\DefaultAdminDataSyncingEventFactory;


class CompanyDefaultAdmin extends PixelBaseModel implements EmailAuthenticatable ,OnlyAdminPanelQueryable , CanSyncData

{
    use Notifiable , EmailAuthenticatableMethods ;

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

    public function Company()  : BelongsTo
    {
        return $this->belongsTo( PixelTenancyManager::getTenantCompanyModelClass() , "company_id" , "id");
    }
    public function getConnectionName()
    {
        return config("database.defaultCentralConnection");
    }
 
    /**
     * @return TenantCompany
     */
    public function tenant(): TenantCompany
    {
        return $this->Company;
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
