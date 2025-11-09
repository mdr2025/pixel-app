<?php

namespace PixelApp\Traits;


use PixelApp\CustomLibs\PixelCycleManagers\PixelAppBootingManagers\PixelAppBootingManager;

trait ExportedFileNameGeneratingTrait
{
       /**
     * Generate a file name with tenant abbreviation or name prefix.
     *
     * @param string $fileName
     * @return string
     */
    public static function handleTenantFileName(string $fileName): string
    {
        if(!PixelAppBootingManager::isBootingForMonolithTenancyApp() || !PixelAppBootingManager::isBootingForTenantApp())
        {
            return $fileName;
        }

        return tenant()?->abbreviation ?? (
            str_word_count(tenant()->name) > 1
            ? strtok(tenant()->name, ' ')
            : tenant()->name
        ) . $fileName;
    }
}
