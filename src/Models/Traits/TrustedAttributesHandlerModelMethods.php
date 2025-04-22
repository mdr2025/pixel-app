<?php

namespace PixelApp\Models\Traits;


trait TrustedAttributesHandlerModelMethods
{
    
   public function handleModelAttrs( array $attributes ) : void
   {
        //data checkc can be done here 
        $this->forceFill($attributes) ;
   }
}