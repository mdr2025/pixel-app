<?php

namespace PixelApp\Http\Middleware\AliassedMiddlewares;

use Closure;
use Illuminate\Http\Request;

class ProtectFilesMidlleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->server('REQUEST_URI')){
            abort("300");
        }
        return $next($request);
    }
}
