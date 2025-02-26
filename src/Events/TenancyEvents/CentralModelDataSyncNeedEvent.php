<?php

namespace PixelApp\Events\TenancyEvents;

use PixelApp\Interfaces\TenancyInterfaces\NeedsTenantDataSync;
use Exception;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * When a central app's model need to sync its data with tenant app's model
 */
class CentralModelDataSyncNeedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected NeedsTenantDataSync | Model  $centralModel ;
    protected string | int  $originalIdentifierKeyValue  ;

    /**
     * Create a new event instance.
     *
     * @return void
     * @throws Exception
     */
    public function __construct( Model $centralModel)
    {
        $this->setCentralModel($centralModel)->setOriginalIdentifierKeyValue();
    }

    protected function setOriginalIdentifierKeyValue() : void
    {
         $this->originalIdentifierKeyValue = $this->centralModel->getTenantAppModelIdentifierOriginalValue();
    }

    /**
     * @return int|string
     */
    public function getOriginalIdentifierKeyValue(): int|string
    {
        return $this->originalIdentifierKeyValue;
    }
    /**
     * @return NeedsTenantDataSync
     */
    public function getCentralModel(): NeedsTenantDataSync
    {
        return $this->centralModel;
    }

    /**
     * @param Model $centralModel
     * @return $this
     * @throws Exception
     */
    public function setCentralModel(Model $centralModel): self
    {
        if(  !$centralModel instanceof NeedsTenantDataSync)
        {
            throw new Exception("Central model must be implement NeedsTenantDataSync interface !");
        }
        $this->centralModel = $centralModel;
        return $this;
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
