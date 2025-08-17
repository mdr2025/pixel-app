<?php

namespace PixelApp\Services\UserEncapsulatedFunc\UserTokensHandlers;

use Exception;
use Illuminate\Support\Carbon; 
use Laravel\Passport\Passport;
use Laravel\Passport\Token;
use PixelApp\Models\UsersModule\PixelUser;

class UserTokensGenerator
{
    protected PixelUser $user;
    protected Token $personalAccessToken ;
    protected string $personalAccessTokenString;
    protected string $refreshTokenString;

    public function __construct(PixelUser $user)
    {
        $this->setUser($user);
    }

    /**
     * @param PixelUser $user
     * @return $this
     */
    public function setUser(PixelUser $user): self
    {
        $this->user = $user;
        return $this;
    }

    protected function getRefreshTokenExpiringDateString() : string
    {
        return (new Carbon())->add(Passport::refreshTokensExpireIn());
    }
    
    protected function generateRefreshToken() : self
    {
        $refreshToken = Passport::refreshToken();
  
        $refreshToken->fill([
                                'access_token_id' =>  $this->personalAccessToken->id,
                                'expires_at' => $this->getRefreshTokenExpiringDateString()
                            ])->save();

        $this->refreshTokenString = $refreshToken->id;
        return $this;
    }

    /**
     * @return $this
     * @throws Exception
     */
    protected function generateAccessToken() : self
    {
        if(method_exists($this->user , 'createToken'))
        {
            $personalAccessTokenResult = $this->user->createToken( $this->user->name );
            $this->personalAccessToken = $personalAccessTokenResult->token;
            $this->personalAccessTokenString = $personalAccessTokenResult->accessToken;
            return $this;
        }
        throw new Exception("User model doesn't user HasApiToken trait !");
    }

    /**
     * @throws Exception
     */
    public function generateTokens()  :array
    {
        $this->generateAccessToken()->generateRefreshToken();

        return [
            "access_token" =>  $this->personalAccessTokenString,
            "refresh_token" => $this->refreshTokenString
        ];
    }
}
