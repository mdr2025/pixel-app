<?php

namespace  PixelApp\Services\UsersManagement\StatusChangerServices;

use PixelApp\Notifications\UserNotifications\StatusNotifications\ActiveRegistrationNotification;
use PixelApp\Notifications\UserNotifications\StatusNotifications\InactiveRegistrationNotification;
use PixelApp\Notifications\UserNotifications\StatusNotifications\RejectedRegistrationNotification;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use PixelApp\Services\UserEncapsulatedFunc\CustomUpdatingService;
use PixelApp\Services\UserEncapsulatedFunc\UserSensitiveDataChangers\UserSensitivePropChangers\StatusChanger;

abstract class AccountStatusChanger extends CustomUpdatingService
{

    protected int $status;
    protected ?StatusChanger $statusChanger = null;

    //Here We get the convenient Notification Class by mapping it to status value
    // that is more dynamic and with this solution there is no need to more if conditions
    const StatusNotificationMap = [
        'active' => ActiveRegistrationNotification::class,
        'inactive' => InactiveRegistrationNotification::class,
        'rejected' => RejectedRegistrationNotification::class,
    ];

    protected function getNotificationClass() : ?string
    {
        return static::StatusNotificationMap[$this->user->status] ?? null;
    }
    /**
     * @return bool
     */
    protected function sendStatusChangingNotification(): bool
    {
        $notificationClass = $this->getNotificationClass();
        if( $notificationClass )
        {
            $this->user->notify(new $notificationClass);
            return true;
        }
        return false;
    }

    protected function saveUserChanges() : bool
    {
        if($this->statusChanger->checkAuthenticatableChanging())
        {
            return $this->user->save();
        }
        return false;
    }
    /**
     * @return $this
     * @throws Exception
     */
    protected function setUserRelationships(): self
    {
        return $this;
    }

    /**
     * @throws Exception
     */
    protected function initStatusChanger() : self
    {
        if(!$this->statusChanger)
        {
            $this->statusChanger = (new StatusChanger())->setData( $this->data )->setAuthenticatable( $this->user );
        }
        return $this;
    }
    /**
     * @return $this
     * @throws Exception
     */
    protected function changeUserStatus(): self
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
        $this->checkConditionsBeforeStart()->changeUserStatus()->setUserRelationships();
        if ($this->saveUserChanges())
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
