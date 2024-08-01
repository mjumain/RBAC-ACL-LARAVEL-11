<?php

namespace App\Http\Middleware;

use App\Models\Route;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekAksesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $routes = Route::firstWhere('route', $request->route()?->getName());

        return blank($routes) || $request->user()->can($routes->permission_name)
            ? $next($request)
            : abort(401);

        // return $next($request);
    }
}
