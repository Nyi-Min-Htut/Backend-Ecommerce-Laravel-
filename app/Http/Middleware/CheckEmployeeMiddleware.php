<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckEmployeeMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // Check if user is an Employee
        if (!$user || $user->tokenable_type !== 'App\Models\Employee') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Employee access only.'
            ], 403);
        }

        return $next($request);
    }
}
