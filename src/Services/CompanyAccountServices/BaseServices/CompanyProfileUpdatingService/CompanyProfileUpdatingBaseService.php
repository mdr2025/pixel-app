<?php

namespace PixelApp\Services\CompanyAccountServices\BaseServices\CompanyProfileUpdatingService;
 
use CRUDServices\CRUDServiceTypes\DataWriterCRUDServices\DataWriterCRUDService;
use CRUDServices\CRUDServiceTypes\DataWriterCRUDServices\UpdatingServices\UpdatingService;
use Illuminate\Database\Eloquent\Model;
use PixelApp\Models\CompanyModule\PixelCompany\PixelCompany; 
 
/**
 * @property PixelCompany $Model
 */
abstract class CompanyProfileUpdatingBaseService extends UpdatingService
{

    public function __construct(PixelCompany $company)
    {
        parent::__construct($company);
    }
  
    protected function getModelUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update Your Company Account !";
    }

    protected function getModelUpdatingSuccessMessage(): string
    {
        return "Your Company Account Updated Successfully !";
    }
 
    /**
     * @param array $dataRow
     * @param Model $model
     * @return DataWriterCRUDService
     *
     * We don't want to update any relationships by this service
     * Both defaultAdmin & package relationships must have their own updatingServices
     */
    protected function HandleModelRelationships(array $dataRow, Model $model): DataWriterCRUDService
    {
        return $this;
    }
}
