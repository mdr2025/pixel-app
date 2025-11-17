<?php

namespace PixelApp\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Response;
use PixelApp\Exceptions\ExceptionTypes\UnauthorizedAccessException;
use PixelApp\Helpers\ResponseHelpers;
use PixelApp\Helpers\ValidationHelpers;
use Throwable;

class Handler extends ExceptionHandler
{ 

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        // Handle our custom UnauthorizedAccessException
        $this->renderable(function ( UnauthorizedAccessException $e, $request)
        {
            return $e->render($request);
        });

        // Handle ValidationException to show validation messages in message field
        $this->renderable(function (\Illuminate\Validation\ValidationException $e, $request)
        {
            $errors = $e->errors();
            $errorMessages = ValidationHelpers::getErrorsIndexedArray($errors);
          
            return Response::error($errorMessages, 422);
        });


        /** For Converting Any Thrown Exception Object To JsonException */
         $this->renderable(function (Exception $e, $request) {
             return Response::error($e->getMessage());
         });

         //no need to define any reportable .... the default behavior is enough
    }
}
