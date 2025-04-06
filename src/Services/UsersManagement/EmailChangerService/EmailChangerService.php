<?php
 
namespace  PixelApp\Services\UsersManagement\EmailChangerService;

use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\UserManagementRequests\UserChangeEmailRequest;
use PixelApp\Interfaces\EmailAuthenticatable;
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
            $this->emailChanger = (new EmailChanger())->setData($this->data)->setAuthenticatable($this->model);
        }
        return $this->emailChanger;
    }

    protected function appendAuthenticatabePrimaryToRequest() : void
    {
        request()->merge([
                            $this->model->getKeyName() => $this->model->getKey()
                        ]);
    }
    public function __construct(EmailAuthenticatable $model)
    {
        if(! $model instanceof EmailAuthenticatable)
        {
            dd("The model wanted to change its password must implement EmailAuthenticatable interface");
        }

        parent::__construct($model);
        $this->appendAuthenticatabePrimaryToRequest();
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
            if ($this->model->save())
            {
                $this->emailChanger->fireCommittingEvents();
                return Response::success([], ["Email Changed Successfully"]);
            }
                return Response::error(["Failed To Change Email"]);
        }

        return Response::success([], ["User is up-to-date ... Nothing to Update "]);
    }
}
