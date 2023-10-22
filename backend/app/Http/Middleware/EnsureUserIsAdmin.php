<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Redirect;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next, $redirectToRoute = null)
    {
        if (!$request->user() || $request->user()->type != 'admin') {
            return $request->expectsJson()
                ? abort(403, 'You are not allowed to access this router.')
                : Redirect::guest(route($redirectToRoute ?: '/'));
        }

        return $next($request);
    }
}
