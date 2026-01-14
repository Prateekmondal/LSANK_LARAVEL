<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffRestriction
{
    /**
     * Handle an incoming request.
     * Staff users are only allowed to access the JCR printable view; all other
     * routes that hit JCR, TimeRegister or Checklist controllers are forbidden.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if (!$user || !$user->hasRole('Staff')) {
            return $next($request);
        }

        $actionName = $request->route()->getActionName();
        $routeName = $request->route()->getName();

        // Allow the named JCR print route
        if ($routeName === 'jcr.print') {
            return $next($request);
        }

        // If action is a controller action, check controller and method
        if (is_string($actionName) && strpos($actionName, '@') !== false) {
            [$controllerClass, $method] = explode('@', $actionName);
            $controllerShort = class_basename($controllerClass);

            // Only allow App\Http\Controllers\JcrController@print
            if ($controllerShort === 'JcrController' && $method === 'print') {
                return $next($request);
            }
        }

        // If a Jcr instance is present in route parameters, redirect to print
        foreach ($request->route()->parameters() as $param) {
            if ($param instanceof \App\Models\Jcr) {
                return redirect()->route('jcr.print', $param->id);
            }
        }

        abort(403, 'Only printable JCR view allowed for Staff users.');
    }
}
