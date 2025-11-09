<?php

namespace PixelApp\Events\TenancyEvents\DataSyncingEvents\FromCentralSideEvents;

use Exception;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\Model;
use PixelApp\Events\TenancyEvents\DataSyncingEvents\TenancyDataSyncingEvent;
use PixelApp\Models\CompanyModule\TenantCompany;

/**
 * When a central app's model need to sync its data with tenant app's model
 */
class TenantDBDataSyncingEvent extends TenancyDataSyncingEvent
{ 
    protected TenantCompany $tenant ;
    protected string $tenantAppModelClass ;
    protected string $tenantModelIdKeyName;
    protected string | int  $tenantModelIdKeyValue  ; 

    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Exception
     */
    public function __construct( 
            // Model $centralModel ,
            TenantCompany $tenant ,
            string $tenantAppModelClass , 
            string $modelIdKeyName ,
            string | int $modelIdKeyValue,
            array $modelUpdatedData = [],
            array $modelRelationsUpdatedData = []
            // string $tenantModelIdKeyName ,
            // string | int $tenantModelIdKeyValue,
            // array $syncedAttributeNames
        )
    {
        $this
        //->setCentralModel($centralModel)
             ->setTenant($tenant)
             ->setTenantAppModelClass($tenantAppModelClass)
             ->setModelIdKeyName($modelIdKeyName)
             ->setModelIdKeyValue($modelIdKeyValue)
             ->setModelUpdatedData($modelUpdatedData)
             ->setModelRelationsUpdatedData($modelRelationsUpdatedData);
    }

    
    // public function getCentralModel(): Model
    // {
    //     return $this->getChangedModel();
    // }

    // /**
    //  * @param Model $centralModel
    //  * @return $this
    //  * @throws Exception
    //  */
    // public function setCentralModel(Model $centralModel): self
    // {
    //     $this->setChangedModel($centralModel);
    //     return $this;
    // }

    public function setTenant(TenantCompany $tenant) : self
    {
        $this->tenant = $tenant;
        return $this;
    }

    public function getTenant(): TenantCompany
    {
        return $this->tenant;
    }

    public function setTenantAppModelClass(string $tenantAppModelClass) : self
    {
        $this->tenantAppModelClass = $tenantAppModelClass;
        return $this;
    }

    public function getTenantAppModelClass(): string
    {
        return $this->tenantAppModelClass;
    }
    
    // public function setTenantModelIdKeyName(string $tenantModelIdKeyName): self
    // {
    //     $this->tenantModelIdKeyName = $tenantModelIdKeyName;
    //     return $this;
    // }

    // public function getTenantModelIdKeyName(): string
    // {
    //     return $this->tenantModelIdKeyName;
    // }

    
    // protected function setTenantModelIdKeyValue(string | int $tenantModelIdKeyValue) : self
    // {
    //      $this->tenantModelIdKeyValue = $tenantModelIdKeyValue;
    //      return $this;
    // }

    // public function getTenantModelIdKeyValue()
    // { 
    //     return $this->tenantModelIdKeyValue;
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
