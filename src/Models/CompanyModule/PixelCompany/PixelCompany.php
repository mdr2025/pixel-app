<?php

namespace PixelApp\Models\CompanyModule\PixelCompany;

use PixelApp\Models\PixelBaseModel ;
use PixelApp\Interfaces\HasUUID; 
use CRUDServices\CRUDComponents\CRUDRelationshipComponents\OwnedRelationshipComponent;
use CRUDServices\Interfaces\MustUploadModelFiles;
use CRUDServices\Interfaces\OwnsRelationships;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PixelApp\Models\SystemConfigurationModels\CountryModule\Country; 
use Illuminate\Database\Eloquent\SoftDeletes;
use PixelApp\Interfaces\EmailAuthenticatable;
use PixelApp\Models\Interfaces\OptionalRelationsInterfaces\BelongsToCountry;
use PixelApp\Models\PixelModelManager;
use RuntimeCaching\Interfaces\ParentModelRuntimeCacheInterfaces\NeedToAccessParentRelationships;

abstract class PixelCompany 
        extends PixelBaseModel
        implements  HasUUID , OwnsRelationships , MustUploadModelFiles , NeedToAccessParentRelationships , BelongsToCountry
{
    
    //laravel traits
    use SoftDeletes;
    
    abstract public function getDefaultAdmin()  : EmailAuthenticatable;   

    protected $fillable = [
        'name', 
        'sector',
        'country_id',
        'logo', 
        'address',
        'employees_no',
        'branches_no',
        'cr_no',
        'email',
        'mobile'
    ];

    protected $casts = [
        'employees_no'=>'integer',
        'branches_no'=>'integer',
        'country_id'=>'integer',
    ];

     
    public function getConnectionName()
    {
        return config("database.defaultCentralConnection");
    }
     
    public function getCompanyLogo() : string
    {
        return $this->getFileFullPathAttrValue('logo');
    }
      
    protected function getCountryModelClass() : string
    {
        return PixelModelManager::getModelForModelBaseType(country::class);
    }

    public function country() : BelongsTo
    {
        $modelClass = $this->getCountryModelClass();
        return $this->belongsTo($modelClass)->select('id', 'code', 'name');
    }

    public function getParentRelationshipsDetails(): array
    {
        return [ "country" => $this->getCountryModelClass() ];
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
 

}
