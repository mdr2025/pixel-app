<?php

namespace PixelApp\Config\ConfigFileIdentifiers\Interfaces;

interface MergableConfigFileIdentifier
{ 
    public function DoesItNeedToMerge() : bool;
}