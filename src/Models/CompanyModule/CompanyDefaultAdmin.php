<?php

namespace PixelApp\Models\CompanyModule;

use PixelApp\Models\PixelBaseModel ;
use PixelApp\Interfaces\EmailAuthenticatable;
use PixelApp\Interfaces\TenancyInterfaces\NeedsTenantDataSync;
use PixelApp\Models\WorkSector\UsersModule\User;
use PixelApp\Traits\interfacesCommonMethods\EmailAuthenticatableMethods;
use PixelApp\Traits\interfacesCommonMethods\TenancyDataSyncHelperMethods;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Interfaces\OnlyAdminPanelQueryable;
use Stancl\Tenancy\Contracts\Tenant;

class CompanyDefaultAdmin extends PixelBaseModel implements EmailAuthenticatable ,OnlyAdminPanelQueryable
// , NeedsTenantDataSync
{
    use Notifiable , EmailAuthenticatableMethods ;
    //', TenancyDataSyncHelperMethods;

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
    public function getSyncedAttributeNames(): array
    {
        /**
         * Here we can return any field we want ... there is no need to return the fields those are exist in fillables
         * because we are here customize the data will be saved in database ... it is not data coming from the request
         */
        return [
            $this->getEmailColumnName() ,
            $this->getEmailVerificationDateColumnName(),
            $this->getEmailVerificationTokenColumnName(),
            'first_name',
            'last_name',
            'name',
            'password',
            'mobile',
        ];
    }

    /**
     * @return Tenant | TenantCompany
     */
    public function tenant(): Tenant
    {
        return $this->Company;
    }

    public function getTenantAppModelClass(): string
    {
        return User::class;
    }

    public function getTenantAppModelIdentifierKeyName(): string
    {
        return $this->getEmailColumnName();
    }

    /**
     * @return int|string
     * it is an alias for getOriginalIdentifierValue method
     */
    public function getTenantAppModelIdentifierOriginalValue(): int|string
    {
        return $this->getOriginalIdentifierValue();
    }
}
