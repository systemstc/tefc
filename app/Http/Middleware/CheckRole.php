<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Expand roles so 'exporter' and 'seller' are interchangeable, and operators can access exporter routes
        $expandedRoles = $roles;
        if (in_array('seller', $roles) && !in_array('exporter', $expandedRoles)) {
            $expandedRoles[] = 'exporter';
        }
        if (in_array('exporter', $roles) && !in_array('seller', $expandedRoles)) {
            $expandedRoles[] = 'seller';
        }
        if ((in_array('seller', $roles) || in_array('exporter', $roles)) && !in_array('operator', $expandedRoles)) {
            $expandedRoles[] = 'operator';
        }

        if (!$request->user() || !$request->user()->roles()->whereIn('slug', $expandedRoles)->exists()) {
            if ($request->wantsJson() || !$request->isMethod('GET')) {
                return response()->json(['error' => 'Unauthorized. Insufficient permissions.'], 403);
            }
            
            // Redirect based on what role they actually have
            if ($request->user() && $request->user()->roles()->where('slug', 'admin')->exists()) {
                return redirect('/dashboard')->withErrors('You do not have access to that module.');
            }

            if ($request->user() && $request->user()->roles()->where('slug', 'center')->exists()) {
                return redirect('/center/dashboard')->withErrors('You do not have access to that module.');
            }
            
            if ($request->user() && ($request->user()->roles()->where('slug', 'buyer')->exists() || $request->user()->roles()->whereIn('slug', ['seller', 'exporter', 'operator'])->exists())) {
                return redirect('/exporter-portal')->withErrors(['error' => 'You do not have access to that module.']);
            }

            return redirect('/')->withErrors(['error' => 'Unauthorized access.']);
        }

        return $next($request);
    }
}
