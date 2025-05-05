<?php

namespace PixelApp\Models\UsersModule; 
  
use CRUDServices\Interfaces\MustUploadModelFiles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PixelApp\Database\Factories\UserModule\UserProfileFactory;
use PixelApp\Models\Interfaces\OptionalRelationsInterfaces\BelongsToCountry;
use PixelApp\Models\PixelBaseModel;
use PixelApp\Models\PixelModelManager;
use PixelApp\Models\SystemConfigurationModels\CountryModule\Country;
use RuntimeCaching\Interfaces\ParentModelRuntimeCacheInterfaces\NeedToAccessParentRelationships;

class UserProfile 
      extends PixelBaseModel
      implements MustUploadModelFiles , NeedToAccessParentRelationships , BelongsToCountry
{
    use HasFactory ;

    protected $table = "user_profile";
    protected $primaryKey = "user_id";

    const MARTIAL_STATUSES = ["Single" , "Married"];
    const MILITARY_STATUSES = ["Exempted" , "Service Completed" , "Student"];

    protected $fillable = [
        'country_id',
        'city_id',
        'logo',
        'gender',
        'marital_status',
        'military_status',
        'date_of_birth',
        'passport_number',
        'national_id_number',
        "user_id"
    ];

    public $timestamps = false;

    protected $casts = [
        'country_id' => 'integer',
    ];

    public static function getUserModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo( static::getUserModelClass(), "user_id", "id");
    }

    public function getParentRelationshipsDetails(): array
    {
        return ["user" =>  static::getUserModelClass()  , "country" => Country::class ];
    }

    public function getModelFileInfoArray(): array
    {
        return [
            [ "RequestKeyName" => "logo" ]
        ];
    }
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, "country_id", "id")->select("id", "name" , "code");
    }

//    public function city(): BelongsTo
//    {
//        return $this->belongsTo(City::class, "city_id", "id")->select("id", "name" ,   "country_id");
//    }

    public function getDocumentsStorageFolderName(): string
    {
        return "users/" . $this->user->hashed_id ;
    }

    protected static function newFactory()
    {
        return UserProfileFactory::new();
    }
}
