<?php

namespace PixelApp\Http\Middleware\TenancyCustomMiddlewares;

use Closure;
use Exception;
use Illuminate\Http\Request;

class ApprovedTenantCompany
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     * @throws Exception
     */
    public function handle(Request $request, Closure $next)
    {
        if(tenant()->isApproved())
        {
            return $next($request);
        }
        throw new Exception("This company account is not approved yet !");
    }
}
