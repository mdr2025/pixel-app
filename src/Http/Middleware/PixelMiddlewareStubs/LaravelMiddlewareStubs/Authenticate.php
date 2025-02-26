<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request; 

class Authenticate
{
    public function handle(Request $request, Closure $next)
    {  
        if (auth()->user() && $request->expectsJson()) {
            return $next($request);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
