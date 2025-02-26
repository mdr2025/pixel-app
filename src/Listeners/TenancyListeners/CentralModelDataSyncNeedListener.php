<?php

namespace PixelApp\Listeners\TenancyListeners;

use PixelApp\Events\TenancyEvents\CentralModelDataSyncNeedEvent;
use PixelApp\Interfaces\TenancyInterfaces\NeedsTenantDataSync;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Contracts\Tenant;


class CentralModelDataSyncNeedListener implements ShouldQueue
{
    protected Model|NeedsTenantDataSync $centralModel;
    protected CentralModelDataSyncNeedEvent $event;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @throws Exception
     */
    protected function throwFailingException(?string $msg = null): Exception
    {
        throw new Exception($msg ?? "Can't sync central model 's data  with tenant model table");
    }

    protected function getOnlySyncableAttrs(): array
    {
        return $this->centralModel->only($this->centralModel->getSyncedAttributeNames());
    }

    /**
     * @throws Exception
     */
    protected function getTenantModel(): ?Model
    {
        $TenantAppModelClass = $this->centralModel->getTenantAppModelClass();
        if (!is_subclass_of($TenantAppModelClass, Model::class)) {
            $this->throwFailingException("Tenant app model class must be a Model type !");
        }
        return $TenantAppModelClass::where(
                                                $this->centralModel->getTenantAppModelIdentifierKeyName(),
                                                "=",
                                                $this->event->getOriginalIdentifierKeyValue()
                                            )->first();
    }

    protected function getTenantCompany(): Tenant
    {
        return $this->centralModel->tenant();
    }

    protected function syncDefaultAdminData(): void
    {
        $this->getTenantCompany()->run(function () {
            if (!($this->getTenantModel()?->forceFill($this->getOnlySyncableAttrs())->save() ?? false)) {
                $this->throwFailingException();
            }
        });
    }

    /**
     * @return $this
     */
    protected function setCentralModel(): self
    {
        $this->centralModel = $this->event->getCentralModel();
        return $this;
    }

    protected function setEvent(CentralModelDataSyncNeedEvent $event): self
    {
        $this->event = $event;
        return $this;
    }
    /**
     * Handle the event.
     *
     * @param  CentralModelDataSyncNeedEvent  $event
     * @return void
     */
    public function handle(CentralModelDataSyncNeedEvent $event)
    {
        $this->setEvent($event)->setCentralModel()->syncDefaultAdminData();
    }
}
