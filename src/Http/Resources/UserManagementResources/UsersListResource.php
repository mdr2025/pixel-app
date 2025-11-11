<?php

namespace PixelApp\Http\Resources\UserManagementResources;

use Illuminate\Http\Resources\Json\JsonResource;
use PixelApp\Http\Resources\PixelHttpResourceManager;
use PixelApp\Http\Resources\UserManagementResources\ModelResources\UserProfileResource;

class UsersListResource extends JsonResource
{
    protected function getUserProfileResourceClass() : string
    {
        return PixelHttpResourceManager::getResourceForResourceBaseType(UserProfileResource::class);
    } 

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        
        $data = [
                    "id" => $this->id,
                    "name" => $this->name
                ] ;

        $data = $this->appendPicture($data , $request);
        $this->appendDisablingStatus($data , $request);

        return $data;

    }

    protected function appendDisablingStatus(array &$data , $request) : void
    {
        $hasDepartmentFilter = $request->has('filter.department_id');

        // Ensure 'is_disabled' is a boolean and explicitly check for 'true'
        $isDisabledFilter = $request->has('filter.is_disabled') && filter_var($request->filter['is_disabled'], FILTER_VALIDATE_BOOLEAN) === true;


        $data['isDisabled'] 
        =
        $isDisabledFilter && $hasDepartmentFilter
        ? ($this->department_id != auth('api')->user()->department_id)
        : false;
    }

    protected function appendPicture(array $data , $request) : array
    {
        return array_merge($data , $this->getProfilePictureData($request));
    }

    protected function getProfilePictureData($request) : array
    {
        
        $userProfileResourceClass = $this->getUserProfileResourceClass();
        
        return $this->whenLoaded(
                    'profile',
                    function() use ($userProfileResourceClass , $request)
                    {
                        return (new $userProfileResourceClass($this->profile))?->getPictureData($request) ?? null;
                    }
                );
    }
}