<?php

namespace PixelApp\Interfaces\TenancyInterfaces;

interface NeedsTenancyDataSync
{
    public function getSyncedAttributeNames(): array;
}
