<?php
 
namespace PixelApp\Models\CompanyModule;

use PixelApp\Models\PixelBaseModel;
use PixelApp\Models\SystemConfigurationModels\CountryModule\Country;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use PixelApp\Interfaces\OnlyAdminPanelQueryable;

class CountryPackage extends PixelBaseModel implements OnlyAdminPanelQueryable
{
    use HasFactory;
    protected $fillable = ['package_id', 'country_id', 'old_monthly_price', 'monthly_price', 'annual_price', 'old_annual_price'];
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
