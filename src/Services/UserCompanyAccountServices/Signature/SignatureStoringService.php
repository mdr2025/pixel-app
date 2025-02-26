<?php

namespace PixelApp\Services\UserCompanyAccountServices\Signature; 

use CRUDServices\CRUDServiceTypes\DataWriterCRUDServices\StoringServices\SingleRowStoringService;
use PixelApp\Http\Requests\UserAccountRequests\Signature\SignatureStoringRequest;
use PixelApp\Models\UsersModule\Signature;

class SignatureStoringService extends SingleRowStoringService
{

    protected function getModelCreatingFailingErrorMessage(): string
    {
        return "Failed To Create Signature !";
    }
    protected function getModelCreatingSuccessMessage(): string
    {
        return "The Signature Has Been Created Successfully !";
    }

    protected function getModelClass(): string
    {
        return Signature::class;
    }

    protected function getRequestClass(): string
    {
        return SignatureStoringRequest::class;
    }
}
