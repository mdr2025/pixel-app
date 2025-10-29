<?php

namespace PixelApp\Http\Middleware\AliassedMiddlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class Cors
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
        // Get CORS config (you can store these in config/cors.php)
        $allowedOrigins = Config::get('cors.allowed_origins', ['*']);
        $allowedMethods = Config::get('cors.allowed_methods', ['*']);
        $allowedHeaders = Config::get('cors.allowed_headers', ['*']);
        $maxAge = Config::get('cors.max_age', 0);

        $origin = $request->header('Origin');

        if ($this->isOriginAllowed($allowedOrigins, $origin)) {
            $response = $next($request);

            // Set headers if not already set by Apache server
            if (!$response->headers->has('Access-Control-Allow-Origin')) {
                $response->headers->set('Access-Control-Allow-Origin', $origin);
            }
            if (!$response->headers->has('Access-Control-Allow-Methods')) {
                $response->headers->set('Access-Control-Allow-Methods', implode(', ', $allowedMethods));
            }
            if (!$response->headers->has('Access-Control-Allow-Headers')) {
                $response->headers->set('Access-Control-Allow-Headers', implode(', ', $allowedHeaders));
            }
            $response->headers->set('Access-Control-Max-Age', $maxAge);

            $response->headers->set('Access-Control-Allow-Credentials', ' true');
            
            $response->headers->set('Access-Control-Expose-Headers', 'Has-Encrypted-Data, Encrypted-Props');

            // Handle preflight OPTIONS request to avoid cors error when fetch data by browser
            if ($request->getMethod() === 'OPTIONS') {
                $response->setStatusCode(204);
                $response->setContent('');
            }

            // Log CORS request (only in non-production environments)
            /* if (app()->environment('local', 'staging')) {
                $this->logCorsRequest($request, $response);
            } */

            return $response;
        }

        // Origin not allowed
        return response('Forbidden', 403);
    }

    /**
     * Check if the origin is allowed.
     *
     * @param array $allowedOrigins
     * @param string|null $origin
     * @return bool
     */
    private function isOriginAllowed(array $allowedOrigins, ?string $origin): bool
    {
        if (in_array('*', $allowedOrigins)) {
            return true;
        }

        return in_array($origin, $allowedOrigins);
    }

    /**
     * Log CORS request details for debugging.
     *
     * @param Request $request
     * @param \Illuminate\Http\Response $response
     */
    private function logCorsRequest(Request $request, $response): void
    {
        Log::channel('cors')->info('CORS Request', [
            'method' => $request->getMethod(),
            'url' => $request->fullUrl(),
            'origin' => $request->header('Origin'),
            'response_status' => $response->status(),
            'cors_headers' => [
                'Access-Control-Allow-Origin' => $response->headers->get('Access-Control-Allow-Origin'),
                'Access-Control-Allow-Methods' => $response->headers->get('Access-Control-Allow-Methods'),
                'Access-Control-Allow-Headers' => $response->headers->get('Access-Control-Allow-Headers'),
            ],
        ]);
    }
}
