<?php

namespace PixelApp\Models\UsersModule;

use Illuminate\Support\Str;
use Laravel\Passport\RefreshToken as PassportRefreshToken; 

class RefreshToken extends PassportRefreshToken  
{

    protected $fillable = [
         'id' , 'access_token_id' , 'expires_at' , 'revoked'
    ];

    public function generateTokenId() : string 
    {
        return Str::random(80); 
    }

    public function fill(array $attributes)
    {
        if(!array_key_exists("id" , $attributes))
        {
            $attributes["id"] = $this->generateTokenId();
        }
        
        return parent::fill($attributes);
    }

    public function scopeWhereRefreshTokenId( $query , $tokenId)
    {
        return $query->where('id', $tokenId);
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', "<=" , now() );
    }
    public function scopeNotExpired($query)
    {
        return $query->where('expires_at', ">" , now() );
    }

    public function scopeActive($query)
    {
        return $query->where('revoked', 0);
    }
    
    public function scopeRevoked($query)
    {
        return $query->where('revoked', 1);
    }
}
