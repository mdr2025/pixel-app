<?php

namespace PixelApp\Http\Resources\UserAccountResources;
 
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Http\Resources\UserManagementResources\ModelResources\UserAttachmentsResource;
use PixelApp\Http\Resources\UserManagementResources\ModelResources\UserProfileResource;
use PixelApp\Http\Resources\UserManagementResources\ModelResources\UserResource;

class UserFullResource extends JsonResource
{
    protected Request $request;

    
    protected function getUserAttachmentsResourceClass() : string
    {
        return PixelHttpResourceManager::getResourceForResourceBaseType(UserAttachmentsResource::class);
    }
    protected function appendAttachments(array $dataArrayToChange = []): array
    {
         $dataArrayToChange["user"]["attachments"] = $this->getUserAttachmentsResourceClass()::collection($this->resource->attachments);
        return $dataArrayToChange;
    }

    protected function getUserProfileResourceClass() : string
    {
        return PixelHttpResourceManager::getResourceForResourceBaseType(UserProfileResource::class);
    }

    protected function appendProfileInfo(array $dataArrayToChange = []): array
    {
        $resourceClass = $this->getUserProfileResourceClass();
        $dataArrayToChange["user"]["profile"] = new $resourceClass($this->resource->profile) ;
        return $dataArrayToChange;
    }

    protected function getUserResourceClass() : string
    {
        return PixelHttpResourceManager::getResourceForResourceBaseType(UserResource::class);
    }

    protected function getUserMainData() : array
    {
        $resourceClass = $this->getUserResourceClass();
        return [ "user" =>  (new $resourceClass($this->resource))->toArray($this->request) ];
    }

    protected function setRequest(Request $request) : void
    {
        $this->request = $request;
    }
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //Getting permissions and unsetting from user object before serializing it
        $this->setRequest($request);

        $data = $this->getUserMainData();
        $data = $this->appendProfileInfo($data);
        return  $this->appendAttachments($data);
    }
}
