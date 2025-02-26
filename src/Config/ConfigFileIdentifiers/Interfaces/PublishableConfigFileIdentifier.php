<?php

namespace PixelApp\Config\ConfigFileIdentifiers\Interfaces;

interface PublishableConfigFileIdentifier
{
    public function getConfigPublishGroupingKeyNames() : array;
}