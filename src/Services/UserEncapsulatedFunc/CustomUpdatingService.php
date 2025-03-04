<?php

namespace PixelApp\Services\UserEncapsulatedFunc;

use Exception;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use PixelApp\Interfaces\EmailAuthenticatable;
use PixelApp\Models\UsersModule\PixelUser;
use PixelApp\Services\Traits\GeneralValidationMethods;
use PixelApp\Services\UsersManagement\Interfaces\AdminChangerInterface;

abstract class CustomUpdatingService
{
    use GeneralValidationMethods;

    protected PixelUser | Authenticatable | EmailAuthenticatable $user;

    public function __construct(PixelUser | Authenticatable |EmailAuthenticatable $user)
    {
        $this->user = $user;
    }
    /**
     * @return void
     * @throws Exception
     */
    protected function checkDefaultAdmin() : void
    {
        /**
         * @todo need to review later
         */
        if(!$this->user->isEditableUser()&& !$this instanceof AdminChangerInterface)
        {
            throw new Exception("Can't edit a default admin !");
        }
    }

    /** the common operations will be done (like validation Operations) .... then the main functionality will be called */
    public function change(): JsonResponse | bool
    {
        try {
                $this->checkDefaultAdmin();

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
