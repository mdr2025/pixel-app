<?php

namespace PixelApp\Http\Middleware\AliassedMiddlewares;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    public function handle(Request $request, Closure $next)
    {  
        if (Auth::user() 
    //    && $request->expectsJson() // temporary stopping 
    ) {
            return $next($request);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
}
