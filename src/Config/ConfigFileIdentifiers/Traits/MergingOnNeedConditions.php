<?php

use Illuminate\Support\Facades\File;

trait MergingOnNeedConditions
{
    
    /**
     * Needs to merge hile it is not published to project
     */
    public function DoesItNeedToMerge() : bool
    {
        return !File::exists( config_path( $this->getFileProjectRelevantPath() ) );
    }
}