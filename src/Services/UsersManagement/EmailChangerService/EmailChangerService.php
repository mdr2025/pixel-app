<?php
 
namespace  PixelApp\Services\UsersManagement\EmailChangerService;

use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\UserManagementRequests\UserChangeEmailRequest;
use PixelApp\Models\UsersModule\PixelUser;
use PixelApp\Services\UserEncapsulatedFunc\CustomUpdatingService;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\EmailAuthenticatableSensitivePropChangers\EmailChanger;

class EmailChangerService extends CustomUpdatingService
{
    protected ?EmailChanger $emailChanger = null;

    protected function getRequestFormClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType( UserChangeEmailRequest::class);
    }

    /**
     * @throws Exception
     */
    protected function initEmailChanger() : EmailChanger
    {
        if(!$this->emailChanger)
        {
            $this->emailChanger = (new EmailChanger())->setData($this->data)->setAuthenticatable($this->user);
        }
        return $this->emailChanger;
    }

    protected function appendUserPrimaryToRequest() : void
    {
        request()->merge([
                            "id" => $this->user->id
                        ]);
    }
    public function __construct(Authenticatable|PixelUser $user)
    {
        parent::__construct($user);
        $this->appendUserPrimaryToRequest();
    }

    /**
     * @return JsonResponse
     * @throws Exception
     */
    protected function changerFun(): JsonResponse
    {
        $this->initEmailChanger()->changeAuthenticatableProp();
        if($this->emailChanger->checkAuthenticatableChanging())
        {
            if ($this->user->save())
            {
                $this->emailChanger->fireCommittingEvents();
                return Response::success([], ["Email Changed Successfully"]);
            }
                return Response::error(["Failed To Change Email"]);
        }

        return Response::success([], ["User is up-to-date ... Nothing to Update "]);
    }
}
