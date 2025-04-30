<?php

namespace PixelApp\Services\CompanyAccountServices\BaseServices\CompanyProfileGettingService;

use AuthorizationManagement\PolicyManagement\Policies\BasePolicy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Response;
use PixelApp\Models\CompanyModule\PixelCompany\PixelCompany; 

abstract class CompanyProfileGettingBaseService 
{ 
    
    abstract protected function fetchCompany() : ?PixelCompany;

    abstract protected function getSuccessResponse(PixelCompany $company) : JsonResponse;

    protected function getErrorResponse() : JsonResponse
    {
        return Response::error("There is no such company stored in the database !" , []);
    }
 
    public function getResponse() : JsonResponse
    {
        if($company = $this->fetchCompany())
        { 
            return $this->getSuccessResponse($company);

        }else{
            return $this->getErrorResponse();
        }
        
    }
}
