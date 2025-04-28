<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    
    public function handle(Request $request, Closure $next, $role): Response
    {   

        if (!$request->user()) {
            // If user not logged in, skip role checking
            return $next($request);
        }
    
        if ((int)$request->user()->role !== (int)$role) {
            return redirect('emp-dash');
        }
    
        return $next($request);
    }
}
