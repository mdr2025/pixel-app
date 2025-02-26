<?php

namespace PixelApp\Services\AuthenticationServices\UserAuthServices\LogoutService; 

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Laravel\Passport\Passport;
use Laravel\Passport\Token;
use Lcobucci\JWT\Parser as JwtParser;
use PixelApp\Services\UserEncapsulatedFunc\UserTokensHandlers\UserTokensRevoker;

class LogoutService
{
    protected function initUserTokensRevoker() : UserTokensRevoker
    {
        return new UserTokensRevoker();
    }

    protected function getHeaderHashedAccessToken() : string
    {
        return request()->bearerToken();
    }

    /**
     * @throws Exception
     */
    protected function getAccessTokenId()  :string
    {
        $hashedToken = $this->getHeaderHashedAccessToken();
        $tokenId =  app(JwtParser::class)->parse($hashedToken)->claims()->get('jti');
        if(!$tokenId)
        {
            throw new Exception("The old access token is unable to parse ");
        }
        return $tokenId;
    }
    /**
     * @return Token|null
     * @throws Exception
     */
    protected function fetchAccessTokenInDB()  : Token | null
    {
        $token = Passport::token()->where("id" , $this->getAccessTokenId() )->first();
        if(!$token)
        {
            throw new Exception("The provided access token is invalid ! ");
        }
        return $token;
    }

    /**
     * @throws Exception
     * will use UserTokensRevoker to revoke access token with its refresh tokens
     */
    protected function revokeTokens() : void
    {
        $this->initUserTokensRevoker()->addToRevoke( $this->fetchAccessTokenInDB() )->revoke();
    }
    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {

            DB::beginTransaction();
            $this->revokeTokens();
            DB::commit();
            return Response::success( [] ,  ["User logged out successfully !"]);

        } catch (Exception $e)
        {
            DB::rollBack();
            return  Response::error( [$e->getMessage() ]);
        }
    }
}
