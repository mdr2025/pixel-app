<?php

namespace PixelApp\PixelMacroableExtenders;

use Illuminate\Database\Schema\Blueprint;

class PixelBlueprintExtender extends PixelMacroableExtender
{
    public function extendMacroable() : void
    {
        $this->defineBlueprintStatusMacro();
        $this->defineBlueprintStandardTimeMacro();
    }
    
    protected function defineBlueprintStatusMacro() : void
    {  
        Blueprint::macro('status', function ($default = 1) {
            $this->tinyInteger('status')->default($default);
        });
    }
    protected function defineBlueprintStandardTimeMacro() : void
    { 
        Blueprint::macro('standardTime', function () { 

            $this->timestamp('created_at')->useCurrent();
            $this->timestamp('updated_at')->nullable();
            $this->softDeletes();
            
        });
    }
}
