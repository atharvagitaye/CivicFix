<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaffOrAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('staff')->user() ?: auth('admin')->user();
        if (!$user) {
            return response()->json(['message' => 'Forbidden. Staff or admin only.'], 403);
        }
        return $next($request);
    }
}
