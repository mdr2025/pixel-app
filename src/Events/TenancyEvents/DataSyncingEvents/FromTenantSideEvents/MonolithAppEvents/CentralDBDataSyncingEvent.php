<?php

namespace PixelApp\Events\TenancyEvents\DataSyncingEvents\FromTenantSideEvents\MonolithAppEvents;

use Exception;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\Model;
use PixelApp\Events\TenancyEvents\DataSyncingEvents\TenancyDataSyncingEvent;
use Stancl\Tenancy\Contracts\Tenant;

/**
 * When a tenant app's model need to sync its data with central app's model
 */
class CentralDBDataSyncingEvent extends TenancyDataSyncingEvent
{ 
    protected string $centralAppModelClass ;
    protected string $centralModelIdKeyName;
    protected string | int  $centralModelIdKeyValue  ; 
    protected ?Tenant $tenant = null;

    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Exception
     */
    public function __construct
    (
            // Model $tenantModel,
            string $centralAppModelClass , 
            string $modelIdKeyName ,
            string | int $modelIdKeyValue,
            array $modelUpdatedData,
            array $modelRelationsUpdatedData = []
            // string $centralModelIdKeyName ,
            // string | int $centralModelIdKeyValue,
            // array $syncedAttributeNames
        )
    {
        $this->setTenant()
             //->setTenantModel($tenantModel)
             ->setCentralAppModelClass($centralAppModelClass)
             ->setModelIdKeyName($modelIdKeyName)
             ->setModelIdKeyValue($modelIdKeyValue)
             ->setModelUpdatedData($modelUpdatedData)
             ->setModelRelationsUpdatedData($modelRelationsUpdatedData);
            //  ->setCentralModelIdKeyName($centralModelIdKeyName)
            //  ->setCentralModelIdKeyValue($centralModelIdKeyValue);
    }
 
    /**
     * @param Model $tenantModel
     * @return $this
     * @throws Exception
     */
    // public function setTenantModel(Model $tenantModel): self
    // { 
    //     $this->setChangedModel($tenantModel);
    //     return $this;
    // }
 
    // public function getTenantModel(): Model  
    // {
    //     return $this->getChangedModel();
    // }

    /**
     * @return Tenant|null
     */
    public function getTenant(): ?Tenant
    {
        return $this->tenant;
    }
    /**
     * @param Tenant|null $tenant
     */
    public function setTenant(?Tenant $tenant = null): self
    {
        $this->tenant = $tenant ?? tenant();
        return $this;
    }

    public function setCentralAppModelClass(string $centralAppModelClass) : self
    {
        $this->centralAppModelClass = $centralAppModelClass;
        return $this;
    }

    public function getCentralAppModelClass(): string
    {
        return $this->centralAppModelClass;
    }
    
    // public function setCentralModelIdKeyName(string $centralModelIdKeyName): self
    // {
    //     $this->centralModelIdKeyName = $centralModelIdKeyName;
    //     return $this;
    // }

    // public function getCentralModelIdKeyName(): string
    // {
    //     return $this->centralModelIdKeyName;
    // }

    
    // protected function setCentralModelIdKeyValue(string | int $centralModelIdKeyValue) : self
    // {
    //      $this->centralModelIdKeyValue = $centralModelIdKeyValue;
    //      return $this;
    // }

    // public function getCentralModelIdKeyValue()
    // { 
    //     return $this->centralModelIdKeyValue;
    // }
    
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
