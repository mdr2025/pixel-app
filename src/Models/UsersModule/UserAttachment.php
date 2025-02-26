<?php

namespace PixelApp\Models\UsersModule; 

use PixelApp\Models\PixelBaseModel  ;
use CRUDServices\Interfaces\MustUploadModelFiles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use PixelApp\Models\PixelModelManager;
use RuntimeCaching\Interfaces\ParentModelRuntimeCacheInterfaces\NeedToAccessParentRelationships;

class UserAttachment
extends PixelBaseModel
implements   MustUploadModelFiles, NeedToAccessParentRelationships
{
    use HasFactory ;
    protected $table = "user_attachments";
    protected $fillable = [
        'type',
        'path',
        'path_original',
        'user_id'
    ];
    
    public static function getUserModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }

    public function getParentRelationshipsDetails(): array
    {
        return ["user" => static::getUserModelClass()];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(static::getUserModelClass() , "user_id", "id");
    }

    public function getModelFileInfoArray(): array
    {
        return  [
            ["RequestKeyName" => "path"]
        ];
    }

    public function getDocumentsStorageFolderName(): string
    {
        return "users/" . $this->user->hashed_id ;
    }
}
