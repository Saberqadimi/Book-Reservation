<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{

    public function handle(Request $request, Closure $next, $roles): Response
    {
        $hasAccess = false;

        foreach (explode(".", $roles) as $role) {
            $hasAccess = Auth::user() && Auth::user()->hasRole($role) ? true : $hasAccess;
        }
        if (!$hasAccess) {
            return response()->json(['message' => 'Access denied'], 403);
        }

        return $next($request);
    }
}
