<?php

namespace PixelApp\Services\UserCompanyAccountServices\Signature; 
 
use CRUDServices\CRUDServiceTypes\DataWriterCRUDServices\UpdatingServices\UpdatingService;
use PixelApp\Http\Requests\PixelHttpRequestManager;
use PixelApp\Http\Requests\UserAccountRequests\Signature\SignatureUpdatingRequest;

class SignatureUpdatingService extends UpdatingService
{
    public function getModelUpdatingFailingErrorMessage(): string
    {
        return "Failed To Update The Given Signature !";
    }
    public function getModelUpdatingSuccessMessage(): string
    {
        return "The Signature Has Been Updated Successfully !";
    }

    public function getRequestClass(): string
    {
        return PixelHttpRequestManager::getRequestForRequestBaseType(SignatureUpdatingRequest::class);
    }
}
