<?php

namespace PixelApp\Models\CompanyModule;
 
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;
use Spatie\QueryBuilder\AllowedFilter;  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; 
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
use PixelApp\Interfaces\EmailAuthenticatable;
use PixelApp\Models\CompanyModule\PixelCompany\PixelCompany;
use PixelApp\Models\Interfaces\OptionalRelationsInterfaces\BelongsToCountry;
use PixelApp\Models\Interfaces\TrustedAttributesHandlerModel;
use PixelApp\Models\Interfaces\TrustedRelationAttributesHandlerModel;
use PixelApp\Models\Traits\OptionalRelationstraits\BelongsToCountryMethods;
use PixelApp\Models\Traits\TrustedAttributesHandlerModelMethods;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Interfaces\StatusChangeableAccount;

class TenantCompany extends PixelCompany
                    implements Tenant  , TenantWithDatabase  ,  OnlyAdminPanelQueryable , StatusChangeableAccount , TrustedAttributesHandlerModel , TrustedRelationAttributesHandlerModel 
{

    //laravel traits
    use HasFactory  ;

    //stancl package traits
    use CentralConnection,
        HasDatabase,
        HasInternalKeys,
        HasDataColumn,
        TenantRun;

    //pixel custom traits
    use TrustedAttributesHandlerModelMethods ;


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
        'address',
        'employees_no',
        'branches_no',
        'cr_no',
        'parent_id',
        'type'
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
            'contractor_approved_status',//later
            'main_company_approved_status',//later
            'type',
            'contractor_id',//later
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
     
    
    public function scopeIsNotPending($query)
    {
        $query->whereIn('status', [ 'active' , 'inactive']);
    }

    public function getTenantLogo() : string
    {
        return $this->getFileFullPathAttrValue('logo');
    }

    public function approve() : self
    {
        $this->status = $this->getApprovingStatusValue();
        return $this;
    }

    public function activate() : self
    {
        return $this->approve();
    }

    public function isActive() : bool
    {
        return $this->status == $this->getApprovingStatusValue();
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
        $this->company_id = "CO-" . random_int(100000, 999999);
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
 
    public function getDocumentsStorageFolderName(): string
    {
        return "companies/" . $this->hashed_id;
    }

    public function defaultAdmin()  : HasOne
    {
        $defaultAdminClass = PixelModelManager::getModelForModelBaseType(CompanyDefaultAdmin::class );
        return $this->hasOne($defaultAdminClass , "company_id" , "id");
    }

    public function getDefaultAdmin()  : EmailAuthenticatable
    {
        return $this->defaultAdmin;
    }
    
    public function parent()  : BelongsTo
    {
        return $this->belongsTo(static::class ,'parent_id','id');
    }

    public function childern()  : HasMany
    {
        return $this->hasMany(static::class ,'parent_id','id');
    }
    
   protected function getCompanyContactModelClass() : string
   {
      return PixelModelManager::getModelForModelBaseType(CompanyContact::class);
   }

   public function contacts()
   {
       return $this->hasMany( $this->getCompanyContactModelClass() );
   }
 
   protected function getDefaultAdminModelClass() : string
   {
        return PixelModelManager::getModelForModelBaseType(CompanyDefaultAdmin::class);
   }

   protected function fillDefaultAdminData(array $relationData) : void
   {
        $defaultAdminClass = $this->getDefaultAdminModelClass();
        $defaultAdmin = new $defaultAdminClass();
        
        if($defaultAdmin instanceof TrustedAttributesHandlerModel)
        {
            $defaultAdmin->handleModelAttrs($relationData);
        }else
        {
            $defaultAdmin->fill($relationData);
        }

        $this->setRelation("defaultAdmin" , $defaultAdmin);
   }

   protected function getCountryModelClass() : string
   {
        return PixelModelManager::getModelForModelBaseType(Country::class);
   }

   protected function fillCountryRelationData(array $relationData) : void
   {
        $countryClass = $this->getCountryModelClass();
        $country = new $countryClass();
        
        if($country instanceof TrustedAttributesHandlerModel)
        {
            $country->handleModelAttrs($relationData);
        }else
        {
            $country->fill($relationData);
        }

        $this->setRelation("country" , $country);
   }

   protected function fillRelationData(string $relation , array $relationData) : void
   {
        //not for all relations .... for specific types only
        match($relation)
        {
            "defaultAdmin" => $this->fillDefaultAdminData($relationData) , 
            "country" => $this->fillCountryRelationData($relationData),
        };
   }
 
   public function handleRelationsAttrs(array $relations) : void
   {
        foreach($relations as $relation => $relationData)
        {
            if(is_string( $relation) && is_array($relationData) )
            {
                $this->fillRelationData($relation , $relationData);
            }
        }
   }
}
