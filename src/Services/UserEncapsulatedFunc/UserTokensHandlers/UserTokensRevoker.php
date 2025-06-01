<?php

namespace PixelApp\Services\UserEncapsulatedFunc\UserTokensHandlers;
 
use Laravel\Passport\Passport;
use Laravel\Passport\Token;
use PixelApp\Models\UsersModule\PixelUser;

class UserTokensRevoker
{
    protected array $accessTokenIDS = [];

    public function addToRevoke (Token $tokens) : self
    {
        $this->accessTokenIDS[] = $tokens->id;
        return $this;
    }

    public function addTokensToRevoke(array $tokens) : self
    {
        foreach($tokens as $token)
        {
            $this->addToRevoke($token);
        }

        return $this;
    }

    
    protected function revokeUserAllTokensExceptRecent(PixelUser $user , int $allowedLimit): self
    {
        $availableTokenIds = $user->tokens()->orderBy('created_at' , 'desc')->limit($allowedLimit)->pluck("id");
        $revokableTokens = $user->tokens()->whereNotIn("id" , $availableTokenIds)->get();
        return $this->addTokensToRevoke($revokableTokens);
    }

    /**
     * @param PixelUser $user
     * @return $this
     */
    public function AddUserAccessTokensToRevoke(PixelUser $user): self
    {
        $userAllTokens = $user->tokens ;
        return $this->addTokensToRevoke($userAllTokens);
    }

    public function addToRevokeForNewLogin(PixelUser $user , bool $allowingMultipleDevice = true , int $allowedDevicesNumbers = 5) : self
    {
        if(!$allowingMultipleDevice)
        {
            return $this->AddUserAccessTokensToRevoke($user);
        }

        return $this->revokeUserAllTokensExceptRecent($user , $allowedDevicesNumbers);
    }

    protected function revokeRelatedRefreshTokens() : void
    {
        Passport::refreshToken()->whereIn("access_token_id" , $this->accessTokenIDS)->update(["revoked" => 1]);
    }

    protected function revokeAccessTokens() : void
    {
        app(Passport::tokenModel())->whereIn("id" , $this->accessTokenIDS)->update(["revoked" => 1]);
    }

    public function revoke()  :void
    {
        $this->revokeAccessTokens();
        $this->revokeRelatedRefreshTokens();
    }
}
