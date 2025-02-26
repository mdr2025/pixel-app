<?php

namespace PixelApp\Http\Resources\SystemConfigurationResources\RolesAndPermissions;
 
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //        $encyrpt_algorithm=new phpCipher();
        //
        //        $enc_permission=$encyrpt_algorithm->encrypt(env('ENCY_KEY'),$this->name );

        return [
            "name" => $this->name
        ];
    }
}
