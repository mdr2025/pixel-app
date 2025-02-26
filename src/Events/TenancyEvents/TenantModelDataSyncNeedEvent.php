<?php

namespace PixelApp\Events\TenancyEvents;

use PixelApp\Interfaces\TenancyInterfaces\NeedsCentralDataSync;
use Exception;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Stancl\Tenancy\Contracts\Tenant;

/**
 * When a tenant app's model need to sync its data with central app's model
 */
class TenantModelDataSyncNeedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected Model | NeedsCentralDataSync $tenantModel;
    protected string | int  $originalIdentifierKeyValue  ;
    protected ?Tenant $tenant = null;

    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Exception
     */
    public function __construct(Model $tenantModel)
    {
        $this->setTenant();
        $this->setTenantModel($tenantModel)->setOriginalIdentifierKeyValue();
    }

    protected function setOriginalIdentifierKeyValue() : self
    {
        $this->originalIdentifierKeyValue = $this->tenantModel->getCentralAppModelIdentifierOriginalValue() ;
        return $this;
    }

    /**
     * @return int|string
     */
    public function getOriginalIdentifierKeyValue(): int|string
    {
        return $this->originalIdentifierKeyValue;
    }

    /**
     * @param Model $tenantModel
     * @return $this
     * @throws Exception
     */
    public function setTenantModel(Model $tenantModel): self
    {
        if(!$tenantModel instanceof  NeedsCentralDataSync)
        {
            throw new Exception("Tenant model must be implement NeedsCentralDataSync interface !");
        }
        $this->tenantModel = $tenantModel;
        return $this;
    }

    /**
     * @return Model|NeedsCentralDataSync
     */
    public function getTenantModel(): Model | NeedsCentralDataSync
    {
        return $this->tenantModel;
    }

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
    public function setTenant(?Tenant $tenant = null): void
    {
        $this->tenant = $tenant ?? tenant();
    }

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
