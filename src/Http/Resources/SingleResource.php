<?php

namespace PixelApp\Http\Resources;
 
use Illuminate\Http\Resources\Json\JsonResource; 

class SingleResource extends JsonResource
{
    public function toArray($request)
    {
        return
            [
                "status" => "success",
                "data" => [
                    "item" => parent::toArray($request)
                ]
            ];
    }
}
