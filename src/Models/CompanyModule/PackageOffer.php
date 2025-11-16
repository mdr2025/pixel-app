<?php

namespace PixelApp\Models\CompanyModule;

use PixelApp\Models\PixelBaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PixelApp\Interfaces\OnlyAdminPanelQueryable;

class PackageOffer extends PixelBaseModel implements OnlyAdminPanelQueryable
{
    use HasFactory;
}
