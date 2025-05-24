<?php

namespace PixelApp\Services\UserEncapsulatedFunc;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use PixelApp\Interfaces\EmailAuthenticatable; 
use PixelApp\Services\Traits\GeneralValidationMethods; 

abstract class CustomUpdatingService
{
    use GeneralValidationMethods;

    protected Model | EmailAuthenticatable $model;

    public function __construct(Model |EmailAuthenticatable $model)
    {
        if(!$model instanceof Model)
        {
            dd("The object wanted to update by CustomUpdatingService must be a Modl typed child !");
        }
        
        $this->model = $model;
    }
 
    protected function checkPreConditions() : void
    { 
        return;
    }

    /** the common operations will be done (like validation Operations) .... then the main functionality will be called */
    public function change(): JsonResponse | bool
    {
        try {
                $this->checkPreConditions();

                $this->initValidator()->validateRequest()->setRequestData();

                return $this->changerFun();
        } catch (Exception $e)
        {
            $this->actionWithErrorResponding();
            return Response::error([$e->getMessage()]);
        }
    }

    //every service will define its own change functionality
    abstract protected function changerFun(): JsonResponse | bool;

    /**
     * @return void
     * This Method Allows Child Services To Apply Some Operations When The Updating Failed
     */
    protected function actionWithErrorResponding(): void
    {
        return;
    }
}
