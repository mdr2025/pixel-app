<?php

namespace PixelApp\Listeners\TenancyListeners\DataSyncingListeners\FromCentralSideListeners;
  
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Contracts\Tenant;
use PixelApp\Events\TenancyEvents\DataSyncingEvents\FromCentralSideEvents\TenantDBDataSyncingEvent;

class TenantDBDataSyncingListener implements ShouldQueue
{
    protected Model  $centralModel;
    protected TenantDBDataSyncingEvent $event;

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
        return $this->event->getUpdatedData();
    }

    /**
     * @throws Exception
     */
    protected function getTenantModel(): ?Model
    {
        $TenantAppModelClass = $this->event->getTenantAppModelClass();

        if (!is_subclass_of($TenantAppModelClass, Model::class)) 
        {
            $this->throwFailingException("Tenant app model class must be a Model type !");
        }
        return $TenantAppModelClass::where(
                                                $this->event->getModelIdKeyName(),
                                                "=",
                                                $this->event->getModelIdKeyValue()
                                            )->first();
    }

    protected function getTenantCompany(): Tenant
    {
        return $this->event->getTenant();
    }

    protected function syncTenantModelData(): void
    {
        $this->getTenantCompany()->run(function () {

            if (!($this->getTenantModel()?->forceFill($this->getOnlySyncableAttrs())->save() ?? false))
            {
                $this->throwFailingException();
            }
        });
    }

    /**
     * @return $this
     */
    // protected function setCentralModel(): self
    // {
    //     $this->centralModel = $this->event->getCentralModel();
    //     return $this;
    // }

    protected function setEvent(TenantDBDataSyncingEvent $event): self
    {
        $this->event = $event;
        return $this;
    }
    /**
     * Handle the event.
     *
     * @param  TenantDBDataSyncingEvent  $event
     * @return void
     */
    public function handle(TenantDBDataSyncingEvent $event)
    {
        $this->setEvent($event)
        //->setCentralModel()
        ->syncTenantModelData();
    }
}
