<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class IsAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()->isAdmin()) {
            
            if (! $request->expectsJson()) {
                return Inertia::render('Error', ['status' => 403])->toResponse($request)->setStatusCode(403);
            }
    
            return response('Forbidden.', 403);
        }

        return $next($request);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function _handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check() && $request->user($guard)->isAdmin()) {
                return $next($request);
            }
        }

        if (! $request->expectsJson()) {
            return route('login');
        }

        return response('Forbidden.', 403);
    }
}
