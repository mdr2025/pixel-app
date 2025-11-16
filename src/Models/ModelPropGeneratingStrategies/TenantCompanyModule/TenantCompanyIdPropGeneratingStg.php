<?php 

namespace PixelApp\Models\ModelPropGeneratingStrategies\TenantCompanyModule;

use PixelApp\Models\CompanyModule\TenantCompany;
use PixelApp\Models\ModelPropGeneratingStrategies\ModelPropGeneratingStrategy;

class TenantCompanyIdPropGeneratingStg extends ModelPropGeneratingStrategy
{

    public function __construct(protected TenantCompany $tenantCompany)
    {

    }


    public function generate(): mixed
    {
        $idString = "";

        do{

            $idString = $this->generateCompanyIdString();

        }while($this->checkIfCompanyIdUnique($idString));
        
        return $idString;
    }
    
    protected function checkIfCompanyIdUnique(string $companyIdString) : bool
    {
        $fetching = (bool) $this->tenantCompany->startUniqueCompanyIdQuery($companyIdString)->first();
    
        return !$fetching;
    }

    protected function generateCompanyIdString()  : string
    {
        return "CO-" . random_int(1000, 99999999);
    }

}