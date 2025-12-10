<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckCustomerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
          $user = $request->user();
        
        // Check if user is a Customer
        if (!$user || $user->tokenable_type !== 'App\Models\Customer') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Customer access only.'
            ], 403);
        }

        return $next($request);
    }
}
