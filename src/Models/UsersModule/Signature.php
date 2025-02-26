<?php

namespace PixelApp\Models\UsersModule;

use PixelApp\Models\PixelBaseModel ;
use PixelApp\Scopes\SignatureUserScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PixelApp\Models\PixelModelManager;

class Signature extends PixelBaseModel
{
    use HasFactory;

    protected $table   ='signatures';

    protected $fillable =['user_id','signature'];
 
    public static function getUserModelClass() : string
    {
        return PixelModelManager::getUserModelClass();
    }
    
    public function user()
    {
        return $this->belongsTo(static::getUserModelClass() ,'user_id');
    }

    protected static function booted()
    {
        static::addGlobalScope(new SignatureUserScope());
        static::creating(function (Signature $signature){
            $signature->user_id = auth()->user()->id;
        });
    }


}
