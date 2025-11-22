<?php

namespace PixelApp\Http\Middleware\AliassedMiddlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class LogRequestAndResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Generate unique request ID
        $requestId = (string) Str::uuid();
        $request->headers->set('X-Request-ID', $requestId);

        // Log the request
        $this->logRequest($request, $requestId);

        // Process the request
        $response = $next($request);

        // Log the response
        $this->logResponse($response, $requestId);

        // Add request ID to response for tracking
        $response->headers->set('X-Request-ID', $requestId);

        return $response;
    }

    /**
     * Log the request details
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $requestId
     * @return void
     */
    private function logRequest(Request $request, string $requestId): void
    {
        $method = $request->method();
        $uri = $request->getRequestUri();
        $ip = $request->ip();
        $userAgent = $request->header('User-Agent');

        // Determine what content to log
        $content = $this->getRequestContent($request);

        Log::channel('api')->info("API Request [{$requestId}]", [
            'method' => $method,
            'uri' => $uri,
            'ip' => $ip,
            'user_agent' => $userAgent,
            'user_id' => $request->user()?->id ?? 'unauthenticated',
            'content' => $content,
        ]);
    }

    /**
     * Log the response details
     *
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @param  string  $requestId
     * @return void
     */
    private function logResponse(Response $response, string $requestId): void
    {
        $statusCode = $response->getStatusCode();
        $content = $this->getResponseContent($response);

        $logMethod = $this->getLogMethodByStatusCode($statusCode);

        Log::channel('api')->$logMethod("API Response [{$requestId}]", [
            'status_code' => $statusCode,
            'content' => $statusCode < 227 ? '***** Hidden Data.' : $content,
        ]);
    }

    /**
     * Get the appropriate log method based on status code
     *
     * @param  int  $statusCode
     * @return string
     */
    private function getLogMethodByStatusCode(int $statusCode): string
    {
        if ($statusCode >= 500) {
            return 'error';
        }

        if ($statusCode >= 400) {
            return 'warning';
        }

        return 'info';
    }

    /**
     * Safely get and format request content for logging
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    private function getRequestContent(Request $request): array
    {
        $content = $request->all();

        // Remove sensitive data
        return $this->sanitizeData($content);
    }

    /**
     * Safely get and format response content for logging
     *
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return array|string
     */
    private function getResponseContent(Response $response): array|string
    {
        if ($response instanceof JsonResponse) {
            $content = json_decode($response->getContent(), true) ?? [];
            // Remove sensitive data
            return $this->sanitizeData($content);
        }

        return 'Non-JSON response';
    }

    /**
     * Remove sensitive data from content
     *
     * @param  array  $data
     * @return array
     */
    private function sanitizeData(array $data): array
    {
        //customizable
        $sensitiveFields = ['password', 'password_confirmation', 'secret', 'token', 'credit_card'];

        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = '********';
            }
        }

        // Recursively sanitize nested arrays
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->sanitizeData($value);
            }
        }

        return $data;
    }
}
