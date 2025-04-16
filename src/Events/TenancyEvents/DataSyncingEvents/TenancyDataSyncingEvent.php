<?php

namespace PixelApp\Events\TenancyEvents\DataSyncingEvents;
 
use Exception;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels; 
 
class TenancyDataSyncingEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
 
    protected string $modelIdKeyName ;
    protected string | int $modelIdKeyValue;
    protected array $updatedData = [];
    // protected Model  $chengedModel ;
    // protected array $syncedAttributeNames = []; 
 

    // Getter for $modelIdKeyName
    public function getModelIdKeyName(): string
    {
        return $this->modelIdKeyName;
    }

    // Setter for $modelIdKeyName
    public function setModelIdKeyName(string $modelIdKeyName): self
    {
        $this->modelIdKeyName = $modelIdKeyName;
        return $this;
    }

    // Getter for $modelIdKeyValue
    public function getModelIdKeyValue(): string|int
    {
        return $this->modelIdKeyValue;
    }

    // Setter for $modelIdKeyValue
    public function setModelIdKeyValue(string|int $modelIdKeyValue): self
    {
        $this->modelIdKeyValue = $modelIdKeyValue;
        return $this;
    }

    // Getter for $updatedData
    public function getUpdatedData(): array
    {
        return $this->updatedData;
    }

    // Setter for $updatedData
    public function setUpdatedData(array $updatedData): self
    {
        $this->updatedData = $updatedData;
        return $this;
    }

    // public function getChangedModel(): Model
    // {
    //     return $this->chengedModel;
    // }

    // /**
    //  * @param Model $chengedModel
    //  * @return $this
    //  * @throws Exception
    //  */
    // public function setChangedModel(Model $chengedModel): self
    // {
    //     $this->chengedModel = $chengedModel;
    //     return $this;
    // }
  
    // public function setSyncedAttributeNames(array $syncedAttributeNames): self
    // {
    //     $this->syncedAttributeNames = $syncedAttributeNames;
    //     return $this;
    // }

    // public function getSyncedAttributeNames(): array
    // {
    //     return $this->syncedAttributeNames;
    // }

    public function fireEvent() : void
    {
        event($this);
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
