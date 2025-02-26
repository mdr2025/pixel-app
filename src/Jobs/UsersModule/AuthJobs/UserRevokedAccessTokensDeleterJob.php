<?php

namespace PixelApp\Jobs\UsersModule\AuthJobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use Laravel\Passport\Passport;

class UserRevokedAccessTokensDeleterJob  implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected function deleteAllNotUsableRefreshTokens() : void
    {
        Passport::refreshToken()->where('revoked', 1)->orWhereDate('expires_at', "<" , now() )->delete();
    }
    protected function deleteAllNotRefreshableAccessTokens() : self
    {
        /**
         * @todo later
         * must remove all revoked tokens in all databases
         */
        $days = config("passport.expired_access_token_keeping_days_count");
        $expired = Carbon::now()->subDays($days);
        Passport::token()->where('revoked', 1)->orWhereDate('expires_at', '<', $expired)->delete();
        return $this;
    }
    /**
     * @return void
     * @throws Exception
     */
    public function handle()
    {
       $this->deleteAllNotRefreshableAccessTokens()->deleteAllNotUsableRefreshTokens();;
    }
}
