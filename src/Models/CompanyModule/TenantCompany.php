<?php

namespace PixelApp\Models\CompanyModule;

use PixelApp\Models\PixelBaseModel ;
use PixelApp\Interfaces\HasUUID; 
use CRUDServices\CRUDComponents\CRUDRelationshipComponents\OwnedRelationshipComponent;
use CRUDServices\Interfaces\MustUploadModelFiles;
use CRUDServices\Interfaces\OwnsRelationships;
use Exception;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;
use Spatie\QueryBuilder\AllowedFilter;  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PixelApp\CustomLibs\Tenancy\PixelTenancyManager;
use PixelApp\Interfaces\OnlyAdminPanelQueryable;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\CountryModule\Country;
use Stancl\Tenancy\Contracts\Tenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\CentralConnection;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDataColumn;
use Stancl\Tenancy\Database\Concerns\HasInternalKeys;
use Stancl\Tenancy\Database\Concerns\TenantRun; 
use Stancl\Tenancy\Database\TenantCollection;
use Stancl\Tenancy\Events\CreatingTenant;
use Stancl\Tenancy\Events\DeletingTenant;
use Stancl\Tenancy\Events\SavingTenant;
use Stancl\Tenancy\Events\TenantCreated;
use Stancl\Tenancy\Events\TenantDeleted;
use Stancl\Tenancy\Events\TenantSaved;
use Stancl\Tenancy\Events\TenantUpdated;
use Stancl\Tenancy\Events\UpdatingTenant; 
use Illuminate\Database\Eloquent\SoftDeletes;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Interfaces\StatusChangeableAccount;

class TenantCompany extends PixelBaseModel
                    implements Tenant , HasUUID , TenantWithDatabase , OwnsRelationships , MustUploadModelFiles ,  OnlyAdminPanelQueryable , StatusChangeableAccount
{
    use HasFactory ,  HasDatabase  , SoftDeletes;
    use CentralConnection,
        HasInternalKeys,
        HasDataColumn,
        TenantRun;

    protected $table = "tenant_companies";

    const CompanyAccountAllowedTypes = ["signup" , "company"];
    const CompanyAccountDefaultType = "signup";
    const REGISTRATIONS_DEFAULT_STATUS = "pending";
    const REGISTRATIONS_STATUSES = ["pending" , "active" , "inactive" , "rejected"];

    public $fillable = [
        'name',
        'domain',
        'sector',
        'country_id',
        'logo',
        'mobile',
        'address',
        'employees_no',
        'branches_no',
        'cr_no',
        'parent_id',
        'type',
        'contractor_id',
        'contractor_approved_status',
        'main_company_approved_status'
    ];
    public static function getTableName() : string
    {
        return "tenant_companies";
    }

    public function getOneTimeFillingAttrs() : array
    {
        return ['domain',  'sector'];
    }
     
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'company_id',
            'name',
            'domain',
            'sector',
            'country_id',
            'logo',
            'hashed_id',
            'status',
            'employees_no',
            'branches_no',
            'package_status',
            'mobile',
            'address',
            'cr_no',
            'contractor_approved_status',
            'main_company_approved_status',
            'type',
            'contractor_id',
            'account_type',
            'created_at' ,
            'updated_at' ,
            'accepted_at',
            'deleted_at'
        ];
    }
    protected $casts = [
        'employees_no'=>'integer',
        'branches_no'=>'integer',
        'country_id'=>'integer',
    ];

    public function getConnectionName()
    {
        return config("database.defaultCentralConnection");
    }
    public function getTenantKeyName(): string
    {
        return 'id';
    }

    public function getTenantKey()
    {
        return $this->getAttribute($this->getTenantKeyName());
    }
    public function newCollection(array $models = []): TenantCollection
    {
        return new TenantCollection($models);
    }

    protected $dispatchesEvents = [
        'saving' => SavingTenant::class,
        'saved' => TenantSaved::class,
        'creating' => CreatingTenant::class,
        'created' => TenantCreated::class,
        'updating' => UpdatingTenant::class,
        'updated' => TenantUpdated::class,
        'deleting' => DeletingTenant::class,
        'deleted' => TenantDeleted::class,
    ];

    public function scopeApproved($query)
    {
        $query->where('status',  'active');
    }

    public function scopeIsApproved($query)
    {
        $query->where('status', 'active');
    }
     
    public function getTenantLogo() : string
    {
        return $this->getFileFullPathAttrValue('logo');
    }

    public function isActive() : bool
    {
        return $this->status = $this->getApprovingStatusValue();
    }

    public function isApproved()  : bool
    {
        return $this->isActive();
    }

    
    public function getSignUpAccountStatusChangableValues() : array
    {
        return ["active", "rejected"];
    }
    
    public function getAcceptedAccountStatusChangableValues() : array
    {
        return ["active", "inactive"];
    }

    public function isSystemMemberAccount()  :bool
    {
        return $this->account_type == "company";
    }

    public function isSignUpAccount() : bool
    {
        return $this->account_type == "signup";
    }

    public function getApprovingStatusValue()  :string
    {
        return "active";
    }

    public function getAccountApprovingProps()
    { 
        return [
            "accepted_at" => now(),
            "account_type" => "company"
        ];
    }

    public function getDefaultStatusValue() : string
    {
        return "pending";
    }

    public function approveCompany() : self
    {
        $this->status = "active"; 
        $this->accepted_at = now();
        $this->account_type = "company";
        return $this;
    }

    public function returnToDefaultRegistrationStatus() : self
    {
        $this->status = $this->getDefaultStatusValue(); 
        $this->accepted_at = null;
        $this->account_type = "signup";
        return $this;
    }

    public function generateCompanyIdString()  : self
    {
        $this->company_id = "Co-" . random_int(100000, 999999);
        return $this;
    }


    public function scopeFilter($query)
    {
        AllowedFilter::callback('details', function (Builder $query, $value) {
            $query->Where('name', 'like', "%" . $value . "%")
                ->orWhere('company_id', 'like', "%" . $value . "%")
                ->orWhereHas('defaultAdmin', function ($query) use ($value) {
                    $query->where('email', 'like', "%" . $value . "%")
                          ->orHhere('first_name', 'like', "%" . $value . "%")
                          ->orWhere('last_name', 'like', "%" . $value . "%") ;
                })
                ->orWhereHas('contacts', function ($query) use ($value) {
                    $query->where('contact_No', 'like', "%" . $value . "%");
                });
        });
    }

    public function country()
    {
        return $this->belongsTo(Country::class)->select('id', 'code', 'name');
    }

    public function getOwnedRelationships(): array
    {
        return [
                    OwnedRelationshipComponent::create("defaultAdmin" , "company_id")
               ];
    }

    public function getModelFileInfoArray(): array
    {
        return [
                    [ "RequestKeyName" => "logo"]
               ];
    }
    public function getDocumentsStorageFolderName(): string
    {
        return "companies/" . $this->hashed_id;
    }

    public function defaultAdmin()  : HasOne
    {
        $defaultAdminClass = PixelModelManager::getModelForModelBaseType(CompanyDefaultAdmin::class );
        return $this->hasOne($defaultAdminClass , "company_id" , "id");
    }

    
    public function parent()  : BelongsTo
    {
        return $this->belongsTo(TenantCompany::class ,'parent_id','id');
    }
    public function childern()  : HasMany
    {
        return $this->hasMany(TenantCompany::class,'parent_id','id');
    }
    
   public function contacts()
   {
       return $this->hasMany(CompanyContact::class);
   }

}
