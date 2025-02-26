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
    /**
     * @param PixelUser $user
     * @return $this
     */
    public function AddUserAccessTokensToRevoke(PixelUser $user): self
    {
        foreach ($user->tokens as $token)
        {
            $this->addToRevoke($token);
        }
        return $this;
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
