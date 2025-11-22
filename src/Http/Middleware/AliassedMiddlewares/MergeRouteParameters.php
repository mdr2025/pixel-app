<?php

namespace PixelApp\Http\Middleware\AliassedMiddlewares;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

//NOTE: this is not recommended to be used in production, as it may cause unexpected behavior in the application. It is better to use route model binding or explicitly pass the parameters to the request object.
class MergeRouteParameters
{
    /**
     * Handle an incoming request.
     * 
     * @param Request $request
     * @param Closure $next
     * 
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routeParameters = $request->route()?->parameters() ?? [];
        if (!empty($routeParameters)) {
            $request->mergeIfMissing($routeParameters);
        }

        return $next($request);
    }
}