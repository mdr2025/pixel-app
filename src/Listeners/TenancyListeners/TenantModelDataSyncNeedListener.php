<?php

namespace PixelApp\Listeners\TenancyListeners;

use PixelApp\Events\TenancyEvents\TenantModelDataSyncNeedEvent;
use PixelApp\Interfaces\TenancyInterfaces\NeedsCentralDataSync;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Contracts\Tenant;

class TenantModelDataSyncNeedListener implements ShouldQueue
{

    protected Model | NeedsCentralDataSync $tenantModel ;
    protected TenantModelDataSyncNeedEvent $event;

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
     * @param string|null $msg
     * @return Exception
     * @throws Exception
     */
    protected function throwFailingException(?string $msg = null ) : Exception
    {
        throw new Exception($msg ?? "Can't sync tenant model 's data  with central model table");
    }
    protected function getOnlySyncableAttrs() : array
    {
        return $this->tenantModel->only( $this->tenantModel->getSyncedAttributeNames() );
    }

    /**
     * @throws Exception
     */
    protected function fetchCentralModel() : ?Model
    {
        $TenantAppModelClass = $this->tenantModel->getCentralAppModelClass();
        if(! is_subclass_of($TenantAppModelClass , Model::class))
        {
            $this->throwFailingException("Central app model class must be a Model type !");
        }
        return $TenantAppModelClass::where(
                                                $this->tenantModel->getCentralAppModelIdentifierKeyName() ,
                                                "=",
                                                $this->event->getOriginalIdentifierKeyValue()
                                            )->first();
    }

    /**
     * @throws Exception
     */
    protected function syncTenantDefaultUserData() : void
    {
        if(! ($this->fetchCentralModel()?->forceFill( $this->getOnlySyncableAttrs() )->save() ?? false) )
        {
            $this->throwFailingException();
        }
    }
    protected function setTenantModel() :self
    {
        $this->tenantModel = $this->event->getTenantModel();
        return $this;
    }

    protected function getTenant() : ?Tenant
    {
        return $this->event->getTenant() ;
    }

    protected function setEvent(TenantModelDataSyncNeedEvent $event): void
    {
        $this->event = $event;
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     * @throws Exception
     */
    public function handle(TenantModelDataSyncNeedEvent $event)
    {
        $this->setEvent($event);
        if( $this->getTenant() ) // if there is no tenant ... it is a default admin panel user ... no need to sync anything
        {
            $this->setTenantModel()->syncTenantDefaultUserData();
        }
    }
}
