<?php

namespace PixelApp\Http\Resources\UserAccountResources;
 
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use PixelApp\Http\Resources\UserManagementResources\ModelResources\UserAttachmentsResource;
use PixelApp\Http\Resources\UserManagementResources\ModelResources\UserProfileResource;
use PixelApp\Http\Resources\UserManagementResources\ModelResources\UserResource;

class UserFullResource extends JsonResource
{
    protected Request $request;

    private function appendAttachments(array $dataArrayToChange = []): array
    {
         $dataArrayToChange["user"]["attachments"] = UserAttachmentsResource::collection($this->resource->attachments);
        return $dataArrayToChange;
    }
    private function appendProfileInfo(array $dataArrayToChange = []): array
    {
        $dataArrayToChange["user"]["profile"] = new UserProfileResource($this->resource->profile) ;
        return $dataArrayToChange;
    }

    protected function getUserMainData() : array
    {
        return [ "user" =>  (new UserResource($this->resource))->toArray($this->request) ];
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
