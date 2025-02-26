<?php

namespace PixelApp\Models\CompanyModule;

use PixelApp\Models\PixelBaseModel ;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyContact extends PixelBaseModel
{
    use HasFactory;

    protected $table = "company_contacts";

    protected $fillable = [
        'first_name',
        'last_name',
        'contact_numbers',
    ];

    protected $casts = [
      "contact_numbers"  => "array"
    ];
    public function getConnectionName()
    {
        return config("database.defaultCentralConnection");
    }
    public function company()
    {
        return $this->belongsTo(TenantCompany::class);
    }
}
