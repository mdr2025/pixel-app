<?php

namespace PixelApp\Exceptions\ExceptionTypes;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Exception thrown when a user attempts to access resources they don't have permission for
 * 
 * Business Purpose: Provides clear, user-friendly error messages when access is denied
 * instead of technical database errors
 */
class UnauthorizedAccessException extends Exception
{
    /**
     * The HTTP status code for this exception
     */
    protected $code = 403;

    /**
     * Create a new unauthorized access exception
     *
     * @param string $message The user-friendly error message
     * @param int $code The HTTP status code (default: 403)
     * @param Exception|null $previous Previous exception for chaining
     */
    public function __construct(string $message = 'Access denied', int $code = 403, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * Render the exception as an HTTP response
     * 
     * Business Value: Returns structured error responses that frontend applications
     * can handle gracefully to show appropriate messages to users
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function render(Request $request): JsonResponse
    {
        return response()->json([
            'error' => 'Access Denied',
            'message' => $this->getMessage(),
            'code' => 'UNAUTHORIZED_ACCESS',
            'status' => $this->getCode(),
            'timestamp' => now()->toISOString(),
            // Include additional context for API consumers
            'documentation' => 'Please contact your system administrator if you believe this is an error.',
            'support_reference' => 'REF-' . uniqid()
        ], $this->getCode());
    }

    /**
     * Report the exception to logging system
     * 
     * Business Value: Helps administrators track unauthorized access attempts
     * for security monitoring and compliance auditing
     *
     * @return bool Whether the exception should be reported
     */
    public function report(): bool
    {
        // Always report unauthorized access attempts for security monitoring
        return true;
    }
}
