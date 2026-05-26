<?php

namespace App\Http\Middleware;

use App\Models\Configs\MaintenanceSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSiteIsAvailable
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! MaintenanceSetting::current()->maintenance_enabled) {
            return $next($request);
        }

        if ($request->user()) {
            return $next($request);
        }

        abort(503);
    }
}
