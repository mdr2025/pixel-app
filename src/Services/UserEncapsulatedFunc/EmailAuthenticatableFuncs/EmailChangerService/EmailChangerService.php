<?php
 
namespace  PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\EmailChangerService;

use Exception; 
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response; 
use PixelApp\Interfaces\EmailAuthenticatable; 
use PixelApp\Services\UserEncapsulatedFunc\CustomUpdatingService;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\EmailAuthenticatableSensitivePropChangers\EmailChanger;

class EmailChangerService extends CustomUpdatingService
{ 
    protected ?EmailChanger $emailChanger = null;
  
    /**
     * @throws Exception
     */
    protected function initEmailChanger() : EmailChanger
    {
        if(!$this->emailChanger)
        {
            $this->emailChanger = (new EmailChanger($this->model))->setData($this->data);
        }
        return $this->emailChanger;
    }
 
    public function __construct(EmailAuthenticatable $model)
    {
        if(! $model instanceof EmailAuthenticatable)
        {
            dd("The model wanted to change its password must implement EmailAuthenticatable interface");
        }

        parent::__construct($model); 
    }

    protected function fireCommittingEvents() : void
    {
        $this->emailChanger->fireCommittingDefaultEvents();
    }

    protected function saveModelChanges() : void
    {
        $this->model->save();
    }

    protected function checkAuthenticatableChanging() : bool
    {
        return $this->emailChanger->checkAuthenticatableChanging();
    }

    protected function changeAuthenticatableProp() : void
    {
        $this->initEmailChanger()->changeAuthenticatableProp();
    }
    /**
     * @return JsonResponse
     * @throws Exception
     */
    protected function changerFun(): JsonResponse
    {
        $this->changeAuthenticatableProp();

        if($this->checkAuthenticatableChanging())
        {
            if ($this->saveModelChanges())
            {
                $this->fireCommittingEvents();

                return Response::success([], ["Email Changed Successfully"]);
            }
                return Response::error(["Failed To Change Email"]);
        }

        return Response::success([], ["User is up-to-date ... Nothing to Update "]);
    }
}
