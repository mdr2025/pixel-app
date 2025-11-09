<?php

namespace PixelApp\Listeners\TenancyListeners\DataSyncingListeners\FromCentralSideListeners;
  
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
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
        return $this->event->getModelUpdatedData();
    }

    /**
     * @throws Exception
     */
    protected function getTenantModel(): Model
    {
        $TenantAppModelClass = $this->event->getTenantAppModelClass();

        if (!is_subclass_of($TenantAppModelClass, Model::class)) 
        {
            $this->throwFailingException("Tenant app model class must be a Model type !");
        }
        $tenantModel = $TenantAppModelClass::where(
                                                $this->event->getModelIdKeyName(),
                                                "=",
                                                $this->event->getModelIdKeyValue()
                                            )->first();
        if(!$tenantModel)
        {
            $this->throwFailingException("Failed to find the tenant model need to sync its data in our database ! ... Related to type : " . $TenantAppModelClass);
        }

        return $tenantModel;
    }

    protected function getTenantCompany(): Tenant
    {
        return $this->event->getTenant();
    }

    protected function syncTenantModelData(): void
    {
        $this->getTenantCompany()->run(function () {

            $tenantModel = $this->getTenantModel();
            $this->syncModelData($tenantModel);
            $this->syncModelRelationsData($tenantModel);
            
        });
    }

    
    protected function syncModelData(Model $tenantModel) : void
    {
        if(! ($tenantModel->forceFill( $this->getOnlySyncableAttrs() )->save() ?? false) )
        {
            $this->throwFailingException();
        }
    }

    protected function isAssociatedArray($value) : bool
    {
        return is_array($value) && Arr::isAssoc($value);
    }

    protected function getRelationsUpdatedDataArray() : array
    {
        $array = $this->event->getModelRelationsUpdatedData();
        return array_filter($array, function ($value, $key) {

                    return is_string($key) 
                        &&
                        $this->isAssociatedArray($value ) 
                        &&
                        count($value) > 0;

                }, ARRAY_FILTER_USE_BOTH);

    }
    protected function syncModelRelationsData(Model $tenantModel) : void
    {
        foreach($this->getRelationsUpdatedDataArray() as $relation => $data)
        {
            $this->syncModelRelationData($tenantModel , $relation , $data);
        }
    }

    protected function syncModelRelationData(Model $tenantModel , string $relation , array $data) : void
    {
        if(!$tenantModel->{$relation}()->update($data))
        {
            $this->throwFailingException("Failed to update the tenant moedl relation : $relation");
        }
    }


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
        $this->setEvent($event)->syncTenantModelData();
    }
}
