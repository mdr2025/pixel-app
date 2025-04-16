<?php

namespace PixelApp\Listeners\TenancyListeners\DataSyncingListeners\FromTenantSideListeners\MonolithAppListeners;

use Exception;
use Illuminate\Database\Eloquent\Model;
use PixelApp\Events\TenancyEvents\DataSyncingEvents\FromTenantSideEvents\MonolithAppEvents\CentralDBDataSyncingEvent;
use PixelApp\Listeners\TenancyListeners\DataSyncingListeners\TenancyDataSyncingListener;
use Stancl\Tenancy\Contracts\Tenant;


/**
 * When a tenant app's model need to sync its data with central app's model
 */
class CentralDBDataSyncingListener extends TenancyDataSyncingListener
{ 
    protected Model $tenantModel ;
    protected CentralDBDataSyncingEvent $event;

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
        return $this->event->getUpdatedData();
    }

    /**
     * @throws Exception
     */
    protected function fetchCentralModel() : ?Model
    {
        $centralAppModelClass = $this->event->getCentralAppModelClass();
        
        if(! is_subclass_of($centralAppModelClass , Model::class))
        {
            $this->throwFailingException("Central app model class must be a Model type !");
        }

        return $centralAppModelClass::where(
                                                $this->event->getModelIdKeyName() ,
                                                "=",
                                                $this->event->getModelIdKeyValue()
                                            )->first();
    }

    /**
     * @throws Exception
     */
    protected function syncTenantDefaultUserData() : void
    {
        tenancy()->central(function()
        { 
            if(! ($this->fetchCentralModel()?->forceFill( $this->getOnlySyncableAttrs() )->save() ?? false) )
            {
                $this->throwFailingException();
            }
        });
    }
    // protected function setTenantModel() :self
    // {
    //     $this->tenantModel = $this->event->getTenantModel();
    //     return $this;
    // }

    protected function getTenant() : ?Tenant
    {
        return $this->event->getTenant() ;
    }

    protected function setEvent(CentralDBDataSyncingEvent $event): void
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
    public function handle(CentralDBDataSyncingEvent $event)
    {
        $this->setEvent($event);
        if( $this->getTenant() ) // if there is no tenant ... it is a default admin panel user ... no need to sync anything
        {
            $this
            //->setTenantModel()
            ->syncTenantDefaultUserData();
        }
    }
}
