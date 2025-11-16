<?php

namespace PixelApp\Models\CompanyModule;

use PixelApp\Models\PixelBaseModel ;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PixelApp\Interfaces\OnlyAdminPanelQueryable;

class Package extends PixelBaseModel implements OnlyAdminPanelQueryable
{
    use HasFactory;

    # TABLE NAME
    protected $table = 'packages';

    # FILLABLE ATTRIBUTES
    protected $fillable = [
        'name',
        'description',
        'invoices_count',
        'employees_count',
        'clients_count',
        'vendors_count',
        'inventories_count',
        'treasueries_count',
        'assets_count',
        'quotations_count',
        'banks_accounts_count',
        'purchase_order_count',
        'attachments_size',
        'free_subscrip_period',
        'grace_period',
        'products_count',
    ];

    # START RELATIONS
    public function countryPackages(): HasMany
    {
        return $this->hasMany(CountryPackage::class, 'package_id', 'id');
    }
    # END RELATIONS
}

