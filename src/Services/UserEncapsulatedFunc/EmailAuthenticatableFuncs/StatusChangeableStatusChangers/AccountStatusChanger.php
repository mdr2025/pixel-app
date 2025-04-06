<?php

namespace  PixelApp\Services\UserEncapsulatedFunc\EmailAuthenticatableFuncs\StatusChangeableStatusChangers;

use PixelApp\Notifications\UserNotifications\StatusNotifications\ActiveRegistrationNotification;
use PixelApp\Notifications\UserNotifications\StatusNotifications\InactiveRegistrationNotification;
use PixelApp\Notifications\UserNotifications\StatusNotifications\RejectedRegistrationNotification;
use Exception; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use PixelApp\Interfaces\EmailAuthenticatable;
use PixelApp\Services\UserEncapsulatedFunc\CustomUpdatingService;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\Interfaces\StatusChangeableAccount;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\StatusChanger;


/**
 * @property Model | StatusChangeableAccount $model
 */
abstract class AccountStatusChanger extends CustomUpdatingService
{
    protected int $status;
    protected ?StatusChanger $statusChanger = null;

    //Here We get the convenient Notification Class by mapping it to status value
    // that is more dynamic and with this solution there is no need to more if conditions
    // protected static $statusNotificationMap = [
    //     'active' => ActiveRegistrationNotification::class,
    //     'inactive' => InactiveRegistrationNotification::class,
    //     'rejected' => RejectedRegistrationNotification::class,
    // ];

    public function __construct(Model $model)
    {
        if(!$model instanceof EmailAuthenticatable)
        {   
            dd("The account wanted to change its status must implment EmailAuthenticatable interface");
        }

        
        if(!$model instanceof StatusChangeableAccount)
        {   
            dd("The account wanted to change its status must implment StatusChangeableAccount interface");
        }
  
        parent::__construct($model);
    }

    protected function getNotificationClass() : ?string
    {
        return static::$statusNotificationMap[$this->model->status] ?? null;
    }
    /**
     * @return bool
     */
    protected function sendStatusChangingNotification(): void
    { 
        if( $notificationClass = $this->getNotificationClass() )
        {
            $this->model->notify(new $notificationClass); 
        } 
    }

    protected function saveAuthenticatableChanges() : bool
    {
        if($this->statusChanger->checkAuthenticatableChanging())
        {
            return $this->model->save();
        }
        return false;
    }
 
    /**
     * @throws Exception
     */
    protected function initStatusChanger() : self
    {
        if(!$this->statusChanger)
        {
            $this->statusChanger = (new StatusChanger())->setData( $this->data )->setAuthenticatable( $this->model );
        }
        return $this;
    }
    /**
     * @return $this
     * @throws Exception
     */
    protected function changeAuthenticatableStatus(): self
    {
        $this->initStatusChanger();
        $this->statusChanger->changeAuthenticatableProp();
        return $this;
    }

    /**
     * @return $this
     * @throws Exception
     */
    protected function checkConditionsBeforeStart()  : self
    {
        return $this;
    }
    /**
     * @return JsonResponse
     * @throws Exception
     */
    protected function changerFun(): JsonResponse
    {
        DB::beginTransaction();
        $this->checkConditionsBeforeStart()->changeAuthenticatableStatus();
        if ($this->saveAuthenticatableChanges())
        {
            DB::commit();
            $this->sendStatusChangingNotification();
            return Response::success([], ["Account Status Changed Successfully"]);
        }
        return Response::error(["Failed To Change Account Status !"]);
    }

    protected function actionWithErrorResponding(): void
    {
        DB::rollBack();
    }
}
