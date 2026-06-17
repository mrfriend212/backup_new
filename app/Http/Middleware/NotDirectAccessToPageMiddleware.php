<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NotDirectAccessToPageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isDirectRequest = $request->path() !== '/' && 
                          $request->isMethod('get') && 
                          !$request->ajax() && 
                          $request->server('HTTP_REFERER') === null;
        
        if ($isDirectRequest) {
            abort(404);
        }

        return $next($request);
    }
}
