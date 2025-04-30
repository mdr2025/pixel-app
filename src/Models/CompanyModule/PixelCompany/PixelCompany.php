<?php

namespace PixelApp\Models\CompanyModule\PixelCompany;

use PixelApp\Models\PixelBaseModel ;
use PixelApp\Interfaces\HasUUID; 
use CRUDServices\CRUDComponents\CRUDRelationshipComponents\OwnedRelationshipComponent;
use CRUDServices\Interfaces\MustUploadModelFiles;
use CRUDServices\Interfaces\OwnsRelationships; 
use PixelApp\Models\SystemConfigurationModels\CountryModule\Country; 
use Illuminate\Database\Eloquent\SoftDeletes;
use PixelApp\Interfaces\EmailAuthenticatable;
use RuntimeCaching\Interfaces\ParentModelRuntimeCacheInterfaces\NeedToAccessParentRelationships;

abstract class PixelCompany extends PixelBaseModel
                    implements  HasUUID , OwnsRelationships , MustUploadModelFiles , NeedToAccessParentRelationships
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
      
    public function country()
    {
        return $this->belongsTo(Country::class)->select('id', 'code', 'name');
    }

    public function getParentRelationshipsDetails(): array
    {
        return [ "country" => Country::class ];
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
